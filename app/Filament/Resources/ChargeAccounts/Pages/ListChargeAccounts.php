<?php

namespace App\Filament\Resources\ChargeAccounts\Pages;

use App\Filament\Resources\ChargeAccounts\ChargeAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChargeAccounts extends ListRecords
{
    protected static string $resource = ChargeAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
