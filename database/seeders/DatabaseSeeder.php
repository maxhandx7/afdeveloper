<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Categorías iniciales para las finanzas de un freelance.
     * Es seguro correrlo varias veces: no duplica.
     *
     *   php artisan db:seed --force
     */
    public function run(): void
    {
        $categories = [
            'income' => [
                'Desarrollo a medida' => '#10b981',
                'Mantenimiento / soporte' => '#14b8a6',
                'Consultoría' => '#0ea5e9',
                'Hosting y dominios (reventa)' => '#6366f1',
                'Salario' => '#84cc16',
            ],
            'expense' => [
                'Servidores (VPS, cloud)' => '#f43f5e',
                'Dominios' => '#f97316',
                'Software y suscripciones' => '#8b5cf6',
                'Impuestos y seguridad social' => '#64748b',
                'Equipo y herramientas' => '#ec4899',
                'Educación' => '#eab308',
                'Transporte' => '#06b6d4',
            ],
        ];

        foreach ($categories as $type => $items) {
            foreach ($items as $name => $color) {
                Category::firstOrCreate(['name' => $name, 'type' => $type], ['color' => $color]);
            }
        }
    }
}
