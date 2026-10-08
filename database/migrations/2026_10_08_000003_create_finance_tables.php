<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Clientes reales (los que pagan). Los "clients" viejos eran testimonios.
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('document')->nullable()->comment('NIT o cédula');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 20)->default('bank');
            $table->string('currency', 3)->default('COP');
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 10);
            $table->string('color', 20)->nullable();
            $table->timestamps();

            $table->unique(['name', 'type']);
        });

        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10);
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('COP');
            $table->string('frequency', 20)->default('monthly');
            $table->date('next_due_date');
            $table->date('ends_at')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('finance_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10);
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('COP');
            $table->decimal('exchange_rate', 12, 4)->default(1);
            $table->decimal('amount_base', 15, 2)->comment('Monto convertido a la moneda base');
            $table->date('date');
            $table->string('status', 10)->default('paid');
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('finance_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('recurring_transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('recurring_transactions');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('finance_accounts');
        Schema::dropIfExists('clients');
    }
};
