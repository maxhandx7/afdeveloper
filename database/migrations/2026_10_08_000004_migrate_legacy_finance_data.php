<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Copia los ingresos y gastos del sistema viejo a la nueva tabla transactions.
 * Las tablas incomes/expenses NO se borran: quedan como respaldo hasta que
 * confirmes que todo cuadra. Luego se pueden eliminar en otra migración.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $categories = [
            'income' => DB::table('categories')->insertGetId([
                'name' => 'Sin categoría', 'type' => 'income', 'color' => '#64748b',
                'created_at' => $now, 'updated_at' => $now,
            ]),
            'expense' => DB::table('categories')->insertGetId([
                'name' => 'Sin categoría', 'type' => 'expense', 'color' => '#64748b',
                'created_at' => $now, 'updated_at' => $now,
            ]),
        ];

        foreach (['incomes' => 'income', 'expenses' => 'expense'] as $table => $type) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->orderBy('id')->chunk(500, function ($rows) use ($type, $categories) {
                DB::table('transactions')->insert($rows->map(fn ($row) => [
                    'type' => $type,
                    'description' => $row->description,
                    'amount' => $row->amount,
                    'currency' => 'COP',
                    'exchange_rate' => 1,
                    'amount_base' => $row->amount,
                    'date' => $row->date,
                    'status' => 'paid',
                    'paid_at' => $row->date,
                    'category_id' => $categories[$type],
                    'notes' => 'Migrado del sistema anterior',
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ])->all());
            });
        }
    }

    public function down(): void
    {
        DB::table('transactions')->where('notes', 'Migrado del sistema anterior')->delete();
        DB::table('categories')->where('name', 'Sin categoría')->delete();
    }
};
