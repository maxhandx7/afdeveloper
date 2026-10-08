<?php

use Illuminate\Support\Facades\Schedule;

// Crea los gastos/ingresos recurrentes del día (VPS, dominios, igualas…).
Schedule::command('finance:recurring')->dailyAt('06:00')->withoutOverlapping();

// Limpia trabajos fallidos viejos de la cola.
Schedule::command('queue:prune-failed --hours=720')->weekly();
