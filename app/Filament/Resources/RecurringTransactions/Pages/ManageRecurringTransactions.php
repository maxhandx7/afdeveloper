<?php

namespace App\Filament\Resources\RecurringTransactions\Pages;

use App\Filament\Resources\RecurringTransactions\RecurringTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRecurringTransactions extends ManageRecords
{
    protected static string $resource = RecurringTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
