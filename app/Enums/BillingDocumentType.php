<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingDocumentType: string implements HasLabel
{
    case Quote = 'cotizacion';
    case ChargeAccount = 'cuenta_cobro';

    public function getLabel(): string
    {
        return match ($this) {
            self::Quote => 'Cotización',
            self::ChargeAccount => 'Cuenta de cobro',
        };
    }

    public function prefix(): string
    {
        return match ($this) {
            self::Quote => 'COT',
            self::ChargeAccount => 'CC',
        };
    }

    /** Estados por los que puede pasar cada tipo de documento. */
    public function statuses(): array
    {
        return match ($this) {
            self::Quote => [BillingDocumentStatus::Draft, BillingDocumentStatus::Sent, BillingDocumentStatus::Accepted, BillingDocumentStatus::Rejected],
            self::ChargeAccount => [BillingDocumentStatus::Draft, BillingDocumentStatus::Sent, BillingDocumentStatus::Paid, BillingDocumentStatus::Cancelled],
        };
    }
}
