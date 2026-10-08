<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Un solo modelo para ingresos y gastos (antes eran dos tablas con el mismo
 * código copiado). El campo amount_base guarda el valor convertido a la moneda
 * base para que todos los reportes sumen peras con peras.
 */
class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'description', 'amount', 'currency', 'exchange_rate', 'date',
        'status', 'due_date', 'paid_at', 'category_id', 'finance_account_id',
        'client_id', 'project_id', 'recurring_transaction_id', 'reference',
        'attachment', 'notes', 'external_source', 'external_id',
    ];

    protected $attributes = [
        'currency' => 'COP',
        'exchange_rate' => 1,
        'status' => 'paid',
    ];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
            'currency' => Currency::class,
            'amount' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
            'amount_base' => 'decimal:2',
            'date' => 'date',
            'due_date' => 'date',
            'paid_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Transaction $transaction) {
            if ($transaction->currency === Currency::base()) {
                $transaction->exchange_rate = 1;
            }

            $transaction->amount_base = round((float) $transaction->amount * (float) $transaction->exchange_rate, 2);

            if ($transaction->status === TransactionStatus::Paid) {
                $transaction->paid_at ??= $transaction->date;
            } else {
                $transaction->paid_at = null;
            }
        });
    }

    public function markAsPaid(?CarbonInterface $on = null): void
    {
        $this->status = TransactionStatus::Paid;
        $this->paid_at = $on ?? now();
        $this->save();
    }

    public function isOverdue(): bool
    {
        return $this->status === TransactionStatus::Pending
            && $this->due_date !== null
            && $this->due_date->isBefore(today());
    }

    // ── Scopes ──────────────────────────────────────────────

    #[Scope]
    protected function income(Builder $query): void
    {
        $query->where('type', TransactionType::Income);
    }

    #[Scope]
    protected function expense(Builder $query): void
    {
        $query->where('type', TransactionType::Expense);
    }

    #[Scope]
    protected function paid(Builder $query): void
    {
        $query->where('status', TransactionStatus::Paid);
    }

    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->where('status', TransactionStatus::Pending);
    }

    #[Scope]
    protected function between(Builder $query, CarbonInterface $from, CarbonInterface $to): void
    {
        $query->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
    }

    // ── Relaciones ──────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function recurringTransaction(): BelongsTo
    {
        return $this->belongsTo(RecurringTransaction::class);
    }
}
