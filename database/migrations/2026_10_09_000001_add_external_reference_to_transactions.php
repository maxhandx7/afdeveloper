<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Movimientos que vienen de otros sistemas (CrediTrack): evita duplicarlos si el webhook se reintenta. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('external_source', 30)->nullable()->after('recurring_transaction_id');
            $table->string('external_id', 64)->nullable()->after('external_source');
            $table->unique(['external_source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['external_source', 'external_id']);
            $table->dropColumn(['external_source', 'external_id']);
        });
    }
};
