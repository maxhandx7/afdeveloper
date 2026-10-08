<?php

namespace App\Services\CrediTrack;

use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Client;
use App\Models\Transaction;

/**
 * Convierte eventos de CrediTrack en movimientos de Finanzas:
 *  - Desembolso de un préstamo  → GASTO   "Préstamos: desembolsos"
 *  - Pago recibido (cuota/abono) → INGRESO "Préstamos: recaudos"
 * Así la utilidad que muestra el dashboard es el interés cobrado.
 * Idempotente: si CrediTrack reintenta el mismo evento, no se duplica.
 */
class CrediTrackSync
{
    public const SOURCE = 'creditrack';

    public function handle(string $event, array $data): string
    {
        return match ($event) {
            'loan.created' => $this->loanCreated($data),
            'loan.deleted' => $this->remove("loan:{$data['id']}"),
            'payment.created' => $this->paymentCreated($data),
            'payment.deleted' => $this->remove("payment:{$data['id']}"),
            default => 'ignored',
        };
    }

    private function loanCreated(array $loan): string
    {
        return $this->upsert("loan:{$loan['id']}", [
            'type' => TransactionType::Expense,
            'description' => 'Desembolso préstamo — '.$loan['client']['name'],
            'amount' => $loan['amount'],
            'date' => $loan['start_date'],
            'category_id' => $this->category('Préstamos: desembolsos', TransactionType::Expense, '#f97316'),
            'client_id' => $this->client($loan['client']),
            'reference' => "CrediTrack préstamo #{$loan['id']}",
            'notes' => 'Total a recaudar con intereses: $'.number_format((float) $loan['total_amount'], 0, ',', '.')
                .". Vence el {$loan['due_date']}.",
        ]);
    }

    private function paymentCreated(array $payment): string
    {
        $loan = $payment['loan'];

        return $this->upsert("payment:{$payment['id']}", [
            'type' => TransactionType::Income,
            'description' => 'Pago préstamo — '.$loan['client']['name'],
            'amount' => $payment['amount'],
            'date' => $payment['date'],
            'category_id' => $this->category('Préstamos: recaudos', TransactionType::Income, '#10b981'),
            'client_id' => $this->client($loan['client']),
            'reference' => "CrediTrack préstamo #{$loan['id']}",
            'notes' => trim('Saldo pendiente: $'.number_format((float) $payment['remaining_balance'], 0, ',', '.')
                .'. '.($payment['notes'] ?? '')),
        ]);
    }

    private function upsert(string $externalId, array $attributes): string
    {
        $transaction = Transaction::withTrashed()->firstOrNew([
            'external_source' => self::SOURCE,
            'external_id' => $externalId,
        ]);

        $existed = $transaction->exists;
        $transaction->fill($attributes + ['status' => 'paid', 'currency' => 'COP'])->save();

        if ($transaction->trashed()) {
            $transaction->restore();
        }

        return $existed ? 'updated' : 'created';
    }

    private function remove(string $externalId): string
    {
        $transaction = Transaction::where('external_source', self::SOURCE)->where('external_id', $externalId)->first();
        $transaction?->delete(); // a la papelera: se puede recuperar

        return $transaction ? 'deleted' : 'not_found';
    }

    private function category(string $name, TransactionType $type, string $color): int
    {
        return Category::firstOrCreate(['name' => $name, 'type' => $type], ['color' => $color])->getKey();
    }

    private function client(array $data): int
    {
        $client = filled($data['document'] ?? null)
            ? Client::firstOrNew(['document' => $data['document']])
            : Client::firstOrNew(['name' => $data['name']]);

        if (! $client->exists) {
            $client->fill([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'notes' => 'Creado automáticamente desde CrediTrack.',
            ])->save();
        }

        return $client->getKey();
    }
}
