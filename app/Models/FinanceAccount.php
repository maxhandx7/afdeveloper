<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Enums\Currency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinanceAccount extends Model
{
    protected $fillable = ['name', 'type', 'currency', 'initial_balance', 'is_active'];

    protected $attributes = ['type' => 'bank', 'currency' => 'COP', 'initial_balance' => 0, 'is_active' => true];

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
            'currency' => Currency::class,
            'initial_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /** Saldo real en la moneda de la cuenta: inicial + ingresos pagados − gastos pagados. */
    public function balance(): float
    {
        $totals = $this->transactions()
            ->where('status', TransactionStatus::Paid)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return (float) $this->initial_balance
            + (float) ($totals[TransactionType::Income->value] ?? 0)
            - (float) ($totals[TransactionType::Expense->value] ?? 0);
    }
}
