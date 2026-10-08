<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestMessages extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Mensajes sin leer';

    public static function canView(): bool
    {
        return ContactMessage::unread()->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactMessage::unread()->latest())
            ->paginated(false)
            ->recordUrl(fn (ContactMessage $r) => ContactMessageResource::getUrl('view', ['record' => $r]))
            ->columns([
                TextColumn::make('name')->label('De')->description(fn (ContactMessage $r) => $r->email),
                TextColumn::make('message')->label('Mensaje')->limit(100)->wrap(),
                TextColumn::make('created_at')->label('')->since(),
            ]);
    }
}
