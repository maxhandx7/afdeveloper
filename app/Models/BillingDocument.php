<?php

namespace App\Models;

use App\Enums\BillingDocumentStatus;
use App\Enums\BillingDocumentType;
use App\Enums\Currency;
use App\Support\NumberToWords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Cotización o cuenta de cobro. */
class BillingDocument extends Model
{
    protected $fillable = [
        'type', 'status', 'client_id', 'issue_date', 'due_date', 'currency',
        'items', 'notes', 'source_id', 'transaction_id', 'sent_at', 'paid_at',
    ];

    protected $attributes = ['status' => 'draft', 'currency' => 'COP', 'items' => '[]', 'total' => 0];

    protected function casts(): array
    {
        return [
            'type' => BillingDocumentType::class,
            'status' => BillingDocumentStatus::class,
            'currency' => Currency::class,
            'items' => 'array',
            'total' => 'decimal:2',
            'issue_date' => 'date',
            'due_date' => 'date',
            'sent_at' => 'datetime',
            'paid_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (BillingDocument $doc) {
            $doc->public_token ??= Str::random(40);
            $doc->issue_date ??= today();

            // Consecutivo por tipo, sin huecos ni duplicados aunque se creen dos a la vez.
            DB::transaction(function () use ($doc) {
                $last = static::query()->where('type', $doc->type)->lockForUpdate()->max('sequence');
                $doc->sequence = ($last ?? 0) + 1;
                $doc->number = $doc->type->prefix().'-'.str_pad((string) $doc->sequence, 4, '0', STR_PAD_LEFT);
            });
        });

        static::saving(function (BillingDocument $doc) {
            $doc->items = array_values(array_map(fn ($i) => [
                'description' => trim((string) ($i['description'] ?? '')),
                'quantity' => (float) ($i['quantity'] ?? 1),
                'unit_price' => (float) ($i['unit_price'] ?? 0),
            ], $doc->items ?? []));

            $doc->total = round(collect($doc->items)->sum(fn ($i) => $i['quantity'] * $i['unit_price']), 2);
        });
    }

    public function totalInWords(): string
    {
        return NumberToWords::pesos($this->total);
    }

    public function publicUrl(): string
    {
        return route('documents.public', $this->public_token);
    }

    public function fileName(): string
    {
        return Str::slug($this->type->getLabel()).'-'.$this->number.'.pdf';
    }

    public function isQuote(): bool
    {
        return $this->type === BillingDocumentType::Quote;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_id');
    }
}
