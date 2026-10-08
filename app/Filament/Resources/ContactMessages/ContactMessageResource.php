<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use UnitEnum;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|UnitEnum|null $navigationGroup = 'Sitio';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'mensaje';

    protected static ?string $navigationLabel = 'Bandeja';

    protected static ?string $slug = 'messages';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::unread()->count();

        return $unread > 0 ? (string) $unread : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Section::make('Mensaje')->columnSpan(['lg' => 2])->schema([
                TextEntry::make('message')->hiddenLabel()->prose()
                    ->formatStateUsing(fn (string $state) => nl2br(e($state)))->html(),
            ]),
            Section::make('Contacto')->columnSpan(['lg' => 1])->schema([
                TextEntry::make('name')->label('Nombre'),
                TextEntry::make('email')->label('Correo')->copyable(),
                TextEntry::make('phone')->label('Teléfono')->copyable()->placeholder('—'),
                TextEntry::make('created_at')->label('Recibido')->dateTime('d M Y, h:i A')->since(),
                TextEntry::make('ip_address')->label('IP')->placeholder('—'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn (ContactMessage $record) => $record->read_at ? null : 'font-semibold')
            ->columns([
                IconColumn::make('read_at')->label('')->boolean()
                    ->state(fn (ContactMessage $record) => $record->read_at === null)
                    ->trueIcon(Heroicon::OutlinedEnvelope)->trueColor('primary')
                    ->falseIcon(Heroicon::OutlinedEnvelopeOpen)->falseColor('gray'),
                TextColumn::make('name')->label('De')->searchable()->description(fn (ContactMessage $r) => $r->email),
                TextColumn::make('message')->label('Mensaje')->limit(90)->wrap()->searchable(),
                TextColumn::make('created_at')->label('Recibido')->since()->sortable()
                    ->tooltip(fn (ContactMessage $r) => $r->created_at->translatedFormat('d M Y, h:i A')),
            ])
            ->filters([
                TernaryFilter::make('read_at')->label('Estado')->nullable()
                    ->trueLabel('Leídos')->falseLabel('Sin leer')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('read_at'),
                        false: fn (Builder $query) => $query->whereNull('read_at'),
                    ),
            ])
            ->recordUrl(fn (ContactMessage $record) => static::getUrl('view', ['record' => $record]))
            ->recordActions([
                Action::make('reply')->label('Responder')->icon(Heroicon::OutlinedArrowUturnLeft)->iconButton()
                    ->url(fn (ContactMessage $r) => 'mailto:'.$r->email.'?subject='.rawurlencode('Re: tu mensaje en afdeveloper.com')),
                Action::make('whatsapp')->label('WhatsApp')->icon(Heroicon::OutlinedChatBubbleOvalLeft)->iconButton()->color('success')
                    ->visible(fn (ContactMessage $r) => filled($r->phone))
                    ->url(fn (ContactMessage $r) => 'https://wa.me/'.preg_replace('/\D/', '', (string) $r->phone))
                    ->openUrlInNewTab(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markRead')->label('Marcar como leídos')->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->action(fn (Collection $records) => $records->each->markAsRead())
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Bandeja vacía')
            ->emptyStateDescription('Cuando alguien te escriba desde el sitio aparecerá aquí.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
            'view' => ViewContactMessage::route('/{record}'),
        ];
    }
}
