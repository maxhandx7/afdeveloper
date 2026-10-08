<?php

namespace App\Filament\Resources\FinanceAccounts\Pages;

use App\Filament\Resources\FinanceAccounts\FinanceAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFinanceAccounts extends ManageRecords
{
    protected static string $resource = FinanceAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
