<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Cotizaciones y cuentas de cobro. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);                       // cotizacion | cuenta_cobro
            $table->unsignedInteger('sequence');
            $table->string('number', 20);                     // COT-0001 / CC-0001
            $table->string('status', 20)->default('draft');
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->date('issue_date');
            $table->date('due_date')->nullable();             // vencimiento / validez de la cotización
            $table->string('currency', 3)->default('COP');
            $table->json('items');                            // [{description, quantity, unit_price}]
            $table->decimal('total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('public_token', 48)->unique();     // enlace para el cliente (PDF)
            $table->foreignId('source_id')->nullable()->constrained('billing_documents')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->date('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'sequence']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_documents');
    }
};
