<?php

namespace App\Filament\Resources\ChargeAccounts\Pages;

use App\Filament\Resources\ChargeAccounts\ChargeAccountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChargeAccount extends CreateRecord
{
    protected static string $resource = ChargeAccountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data + ['type' => ChargeAccountResource::TYPE];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
