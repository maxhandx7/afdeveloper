<?php

namespace App\Services\Billing;

use App\Enums\BillingDocumentStatus;
use App\Enums\BillingDocumentType;
use App\Enums\TransactionType;
use App\Mail\BillingDocumentMail;
use App\Models\BillingDocument;
use App\Models\Business;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\Waha\WahaClient;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class BillingDocuments
{
    public const DEFAULT_IVA_NOTE = 'Declaro que no soy responsable del impuesto sobre las ventas (IVA) ni estoy obligado a expedir factura electrónica.';

    public function __construct(private WahaClient $waha) {}

    public function pdf(BillingDocument $doc): string
    {
        $doc->loadMissing('client');
        $business = Business::current();

        return Pdf::loadView('pdf.billing-document', [
            'doc' => $doc,
            'business' => $business,
            'billing' => $business->setting('billing', []),
            'signature' => $this->signaturePath($business),
        ])
            ->setPaper('letter')
            ->setOption(['isRemoteEnabled' => false, 'chroot' => [public_path(), storage_path('app')]])
            ->output();
    }

    /** Envía por correo (con el PDF adjunto) y/o por WhatsApp (con el enlace). Devuelve los canales usados. */
    public function send(BillingDocument $doc, bool $email, bool $whatsapp): array
    {
        $doc->loadMissing('client');
        $sent = [];

        if ($email) {
            if (blank($doc->client->email)) {
                throw new RuntimeException('El cliente no tiene correo registrado.');
            }
            Mail::to($doc->client->email)->queue(new BillingDocumentMail($doc));
            $sent[] = 'correo';
        }

        if ($whatsapp) {
            if (blank($doc->client->phone)) {
                throw new RuntimeException('El cliente no tiene teléfono registrado.');
            }
            if (! $this->waha->enabled()) {
                throw new RuntimeException('WhatsApp (WAHA) no está activo.');
            }
            $this->waha->sendText($doc->client->phone, $this->whatsAppText($doc));
            $sent[] = 'WhatsApp';
        }

        if ($sent !== [] && $doc->status === BillingDocumentStatus::Draft) {
            $doc->status = BillingDocumentStatus::Sent;
        }
        $doc->sent_at = now();
        $doc->save();

        return $sent;
    }

    /** Marca la cuenta de cobro como pagada y registra el ingreso en Movimientos (una sola vez). */
    public function markAsPaid(BillingDocument $doc, CarbonInterface $paidOn, ?int $accountId = null): Transaction
    {
        if ($doc->isQuote()) {
            throw new RuntimeException('Las cotizaciones no se pagan: conviértela en cuenta de cobro.');
        }

        return DB::transaction(function () use ($doc, $paidOn, $accountId) {
            $transaction = $doc->transaction ?? Transaction::create([
                'type' => TransactionType::Income,
                'description' => "Cuenta de cobro {$doc->number} — {$doc->client->name}",
                'amount' => $doc->total,
                'currency' => $doc->currency,
                'date' => $paidOn,
                'status' => 'paid',
                'paid_at' => $paidOn,
                'client_id' => $doc->client_id,
                'finance_account_id' => $accountId,
                'category_id' => Category::firstOrCreate(
                    ['name' => 'Cuentas de cobro', 'type' => TransactionType::Income],
                    ['color' => '#2546ff'],
                )->getKey(),
                'reference' => $doc->number,
            ]);

            $doc->forceFill([
                'status' => BillingDocumentStatus::Paid,
                'paid_at' => $paidOn,
                'transaction_id' => $transaction->getKey(),
            ])->save();

            return $transaction;
        });
    }

    /** Cotización aceptada → nueva cuenta de cobro con los mismos conceptos. */
    public function convertToChargeAccount(BillingDocument $quote): BillingDocument
    {
        if (! $quote->isQuote()) {
            throw new RuntimeException('Solo las cotizaciones se pueden convertir.');
        }

        return DB::transaction(function () use ($quote) {
            $quote->update(['status' => BillingDocumentStatus::Accepted]);

            return BillingDocument::create([
                'type' => BillingDocumentType::ChargeAccount,
                'client_id' => $quote->client_id,
                'currency' => $quote->currency,
                'items' => $quote->items,
                'notes' => $quote->notes,
                'issue_date' => today(),
                'due_date' => today()->addDays(15),
                'source_id' => $quote->getKey(),
            ]);
        });
    }

    public function whatsAppText(BillingDocument $doc): string
    {
        $name = strtok(trim($doc->client->name), ' ');
        $total = '$'.number_format((float) $doc->total, 0, ',', '.');
        $label = mb_strtolower($doc->type->getLabel());

        return "Hola {$name} 👋\n\n"
            ."Te comparto la {$label} *{$doc->number}* por *{$total}*"
            .($doc->due_date && ! $doc->isQuote() ? ', con vencimiento el '.$doc->due_date->translatedFormat('j \d\e F') : '')
            .".\n\n📄 {$doc->publicUrl()}\n\n"
            .'Quedo atento a cualquier duda.'."\n— ".Business::current()->setting('billing.holder_name', 'Alan Carabali');
    }

    private function signaturePath(Business $business): ?string
    {
        $file = $business->setting('billing.signature');

        return $file && is_file($path = storage_path('app/private/'.$file)) ? $path : null;
    }
}
