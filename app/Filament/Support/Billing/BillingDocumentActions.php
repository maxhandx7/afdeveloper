<?php

namespace App\Filament\Support\Billing;

use App\Enums\BillingDocumentStatus;
use App\Filament\Resources\ChargeAccounts\ChargeAccountResource;
use App\Models\BillingDocument;
use App\Services\Billing\BillingDocuments;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use RuntimeException;

class BillingDocumentActions
{
    public static function pdf(): Action
    {
        return Action::make('pdf')
            ->label('Ver PDF')
            ->icon(Heroicon::OutlinedEye)
            ->color('gray')
            ->url(fn (BillingDocument $record) => route('documents.preview', $record))
            ->openUrlInNewTab();
    }

    public static function send(): Action
    {
        return Action::make('send')
            ->label('Enviar')
            ->icon(Heroicon::OutlinedPaperAirplane)
            ->color('primary')
            ->visible(fn (BillingDocument $record) => $record->status->isEditable())
            ->modalHeading(fn (BillingDocument $record) => "Enviar {$record->number} a {$record->client->name}")
            ->modalSubmitActionLabel('Enviar')
            ->schema(fn (BillingDocument $record) => [
                Toggle::make('email')->label('Por correo (con el PDF adjunto)')
                    ->helperText($record->client->email ?: 'El cliente no tiene correo registrado')
                    ->default(filled($record->client->email))->disabled(blank($record->client->email)),
                Toggle::make('whatsapp')->label('Por WhatsApp (con el enlace al PDF)')
                    ->helperText($record->client->phone ?: 'El cliente no tiene teléfono registrado')
                    ->default(filled($record->client->phone))->disabled(blank($record->client->phone)),
            ])
            ->action(function (BillingDocument $record, array $data) {
                try {
                    $sent = app(BillingDocuments::class)->send($record, (bool) ($data['email'] ?? false), (bool) ($data['whatsapp'] ?? false));
                    Notification::make()->success()
                        ->title($sent ? 'Enviada por '.implode(' y ', $sent) : 'No se eligió ningún canal')->send();
                } catch (RuntimeException $e) {
                    Notification::make()->danger()->title('No se pudo enviar')->body($e->getMessage())->send();
                }
            });
    }

    public static function markAsPaid(): Action
    {
        return Action::make('markAsPaid')
            ->label('Marcar pagada')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (BillingDocument $record) => ! $record->isQuote() && $record->status->isEditable())
            ->modalHeading(fn (BillingDocument $record) => "Registrar pago de {$record->number}")
            ->modalDescription('Se crea el ingreso en Movimientos, ligado al cliente.')
            ->schema([
                DatePicker::make('paid_at')->label('Fecha de pago')->default(today())->native(false)->required(),
                Select::make('finance_account_id')->label('¿A qué cuenta entró?')
                    ->options(fn () => \App\Models\FinanceAccount::where('is_active', true)->pluck('name', 'id')),
            ])
            ->action(function (BillingDocument $record, array $data) {
                app(BillingDocuments::class)->markAsPaid($record, Carbon::parse($data['paid_at']), $data['finance_account_id'] ?? null);
                Notification::make()->success()->title("{$record->number} pagada")->body('El ingreso quedó registrado en Movimientos.')->send();
            });
    }

    public static function convert(): Action
    {
        return Action::make('convert')
            ->label('Convertir en cuenta de cobro')
            ->icon(Heroicon::OutlinedArrowRightCircle)
            ->color('success')
            ->visible(fn (BillingDocument $record) => $record->isQuote() && $record->status->isEditable())
            ->requiresConfirmation()
            ->modalDescription('La cotización queda como aceptada y se crea una cuenta de cobro con los mismos conceptos.')
            ->action(function (BillingDocument $record, $livewire) {
                $new = app(BillingDocuments::class)->convertToChargeAccount($record);
                Notification::make()->success()->title("Creada la cuenta de cobro {$new->number}")->send();
                $livewire->redirect(ChargeAccountResource::getUrl('edit', ['record' => $new]));
            });
    }

    public static function close(): Action
    {
        return Action::make('close')
            ->label(fn (BillingDocument $record) => $record->isQuote() ? 'Rechazada' : 'Anular')
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->visible(fn (BillingDocument $record) => $record->status === BillingDocumentStatus::Sent)
            ->requiresConfirmation()
            ->action(fn (BillingDocument $record) => $record->update([
                'status' => $record->isQuote() ? BillingDocumentStatus::Rejected : BillingDocumentStatus::Cancelled,
            ]));
    }

    public static function duplicate(): Action
    {
        return Action::make('duplicate')
            ->label('Duplicar')
            ->icon(Heroicon::OutlinedDocumentDuplicate)
            ->color('gray')
            ->action(function (BillingDocument $record, $livewire) {
                $copy = BillingDocument::create([
                    'type' => $record->type, 'client_id' => $record->client_id, 'currency' => $record->currency,
                    'items' => $record->items, 'notes' => $record->notes,
                    'issue_date' => today(), 'due_date' => today()->addDays(15),
                ]);
                Notification::make()->success()->title("Creada {$copy->number}")->send();
                $livewire->redirect($livewire::getResource()::getUrl('edit', ['record' => $copy]));
            });
    }
}
