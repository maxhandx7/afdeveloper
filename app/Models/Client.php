<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Cliente que paga (no confundir con los testimonios del sitio). */
class Client extends Model
{
    protected $fillable = ['name', 'company', 'document', 'email', 'phone', 'city', 'notes'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function recurringTransactions(): HasMany
    {
        return $this->hasMany(RecurringTransaction::class);
    }

    public function totalBilled(): float
    {
        return (float) $this->transactions()
            ->where('type', TransactionType::Income)
            ->where('status', TransactionStatus::Paid)
            ->sum('amount_base');
    }

    public function totalOwed(): float
    {
        return (float) $this->transactions()
            ->where('type', TransactionType::Income)
            ->where('status', TransactionStatus::Pending)
            ->sum('amount_base');
    }
}
