<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use Illuminate\Console\Command;

class GenerateRecurringTransactions extends Command
{
    protected $signature = 'finance:recurring';

    protected $description = 'Genera como pendientes los movimientos recurrentes que ya vencieron';

    public function handle(): int
    {
        $total = 0;

        RecurringTransaction::dueQuery()->each(function (RecurringTransaction $recurring) use (&$total) {
            $count = $recurring->generateDueTransactions();
            $total += $count;
            $this->line("• {$recurring->description}: {$count}");
        });

        $this->info("Listo: {$total} movimiento(s) generado(s).");

        return self::SUCCESS;
    }
}
