<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\Frequency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/** Plantilla de un movimiento que se repite (VPS, dominios, iguala de un cliente…). */
class RecurringTransaction extends Model
{
    protected $fillable = [
        'type', 'description', 'amount', 'currency', 'frequency', 'next_due_date',
        'ends_at', 'category_id', 'finance_account_id', 'client_id', 'is_active',
    ];

    // Igual a los defaults de la BD, para que el modelo recién creado ya los tenga.
    protected $attributes = [
        'currency' => 'COP',
        'frequency' => 'monthly',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'currency' => Currency::class,
            'frequency' => Frequency::class,
            'amount' => 'decimal:2',
            'next_due_date' => 'date',
            'ends_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public static function dueQuery(?CarbonInterface $until = null): Builder
    {
        return static::query()
            ->where('is_active', true)
            ->whereDate('next_due_date', '<=', ($until ?? today())->toDateString());
    }

    /**
     * Crea como "pendiente" cada ocurrencia vencida y mueve la próxima fecha.
     * Si el servidor estuvo caído varios días, se pone al día sin duplicar.
     *
     * @return int cantidad de movimientos generados
     */
    public function generateDueTransactions(?CarbonInterface $until = null): int
    {
        $until ??= today();
        $created = 0;

        DB::transaction(function () use ($until, &$created) {
            while ($this->is_active && $this->next_due_date->lte($until)) {
                if ($this->ends_at && $this->next_due_date->gt($this->ends_at)) {
                    $this->is_active = false;
                    break;
                }

                $this->transactions()->create([
                    'type' => $this->type,
                    'description' => $this->description,
                    'amount' => $this->amount,
                    'currency' => $this->currency,
                    'date' => $this->next_due_date,
                    'due_date' => $this->next_due_date,
                    'status' => TransactionStatus::Pending,
                    'category_id' => $this->category_id,
                    'finance_account_id' => $this->finance_account_id,
                    'client_id' => $this->client_id,
                ]);

                $this->next_due_date = $this->frequency->advance($this->next_due_date);
                $created++;
            }

            $this->save();
        });

        return $created;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

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
}
