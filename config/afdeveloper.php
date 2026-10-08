<?php

return [
    // Correo que recibe los mensajes del formulario de contacto.
    'contact_email' => env('CONTACT_EMAIL', 'alancarabali@gmail.com'),

    // Moneda en la que se consolidan los reportes financieros.
    'base_currency' => env('FINANCE_BASE_CURRENCY', 'COP'),

    // Correos con acceso al panel /admin (separados por coma).
    // Vacío = cualquier usuario existente en la tabla users.
    'admin_emails' => array_filter(array_map('trim', explode(',', (string) env('ADMIN_EMAILS', '')))),
];
