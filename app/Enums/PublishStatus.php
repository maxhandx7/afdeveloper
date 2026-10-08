<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Se conservan los valores originales de la columna enum ("ACTIVE" /
 * "DESACTIVATED") para no tener que alterar el esquema en producción.
 */
enum PublishStatus: string implements HasColor, HasLabel
{
    case Published = 'ACTIVE';
    case Hidden = 'DESACTIVATED';

    public function getLabel(): string
    {
        return match ($this) {
            self::Published => 'Publicado',
            self::Hidden => 'Oculto',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Published => 'success',
            self::Hidden => 'gray',
        };
    }
}
