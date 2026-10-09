<?php

namespace App\Filament\Resources\ChargeAccounts\Pages;

use App\Filament\Resources\ChargeAccounts\ChargeAccountResource;
use App\Filament\Support\Billing\BillingDocumentActions;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChargeAccount extends EditRecord
{
    protected static string $resource = ChargeAccountResource::class;

    public function getTitle(): string
    {
        return $this->getRecord()->type->getLabel().' '.$this->getRecord()->number;
    }

    protected function getHeaderActions(): array
    {
        return [
            BillingDocumentActions::pdf(),
            BillingDocumentActions::send(),
            BillingDocumentActions::markAsPaid(),
            BillingDocumentActions::convert(),
            ActionGroup::make([
                BillingDocumentActions::duplicate(),
                BillingDocumentActions::close(),
                DeleteAction::make(),
            ]),
        ];
    }
}
