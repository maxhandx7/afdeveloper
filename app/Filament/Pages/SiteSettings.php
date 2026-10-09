<?php

namespace App\Filament\Pages;

use App\Filament\Support\ContentFields;
use App\Models\Business;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class SiteSettings extends Page
{
    protected string $view = 'filament.pages.site-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Sitio';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Configuración';

    protected static ?string $title = 'Configuración del sitio';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Marca')->columns(2)->schema([
                        TextInput::make('name')->label('Nombre')->required(),
                        TextInput::make('nit')->label('NIT'),
                        ContentFields::image('logo', 'image')->label('Foto de "Sobre mí"')->columnSpanFull(),
                        ContentFields::richText('description', 'Sobre mí'),
                        Textarea::make('mision')->label('Misión')->rows(3),
                        Textarea::make('vision')->label('Visión')->rows(3),
                    ]),
                    Section::make('Inicio')->schema([
                        TextInput::make('configurations.availability')->label('Mensaje de disponibilidad')
                            ->placeholder('Disponible para proyectos freelance')
                            ->helperText('Aparece arriba del título del inicio con un punto verde. Vacío = no se muestra.')
                            ->maxLength(80),
                    ]),
                    Section::make('Cuentas de cobro')
                        ->description('Datos que aparecen en las cuentas de cobro y cotizaciones en PDF.')
                        ->columns(3)
                        ->collapsible()
                        ->schema([
                            TextInput::make('configurations.billing.holder_name')->label('Nombre completo')
                                ->placeholder('Alan Ferrney Carabali Paz'),
                            TextInput::make('configurations.billing.holder_document')->label('Cédula'),
                            TextInput::make('configurations.billing.city')->label('Ciudad')->placeholder('Cali'),
                            Textarea::make('configurations.billing.bank_details')->label('Datos para el pago')->rows(3)
                                ->placeholder("Bancolombia — Ahorros No. …\nNequi: …")->columnSpan(2),
                            FileUpload::make('configurations.billing.signature')->label('Firma (imagen PNG)')
                                ->disk('local')->directory('billing')->visibility('private')
                                ->image()->maxSize(1024)
                                ->helperText('Fondo transparente o blanco. Se guarda en privado.'),
                            Toggle::make('configurations.billing.iva_note_enabled')->label('Incluir nota de "no responsable de IVA"')->default(true),
                            Textarea::make('configurations.billing.iva_note')->label('Texto de la nota')->rows(2)->columnSpan(2)
                                ->placeholder(\App\Services\Billing\BillingDocuments::DEFAULT_IVA_NOTE)
                                ->helperText('Vacío = se usa el texto sugerido. Confírmalo con tu contador.'),
                        ]),
                    Section::make('Contacto')->columns(3)->schema([
                        TextInput::make('mail')->label('Correo')->email()->required(),
                        TextInput::make('phone')->label('Teléfono')->tel(),
                        TextInput::make('address')->label('Dirección / ciudad'),
                        TextInput::make('configurations.whatsapp')->label('WhatsApp')
                            ->helperText('Con indicativo, ej: 573001234567. Activa el botón flotante.'),
                    ]),
                    Section::make('Redes')->columns(2)->schema([
                        TextInput::make('configurations.linkedin')->label('LinkedIn')->url(),
                        TextInput::make('configurations.github')->label('GitHub')->url(),
                        TextInput::make('configurations.instagram')->label('Instagram')->url(),
                        TextInput::make('configurations.facebook')->label('Facebook')->url(),
                        TextInput::make('configurations.twitter')->label('X / Twitter')->url(),
                    ]),
                    Section::make('SEO')->schema([
                        Textarea::make('configurations.seo_description')->label('Descripción para Google')
                            ->rows(2)->maxLength(160)
                            ->helperText('Lo que sale debajo del título en los resultados de búsqueda.'),
                    ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')->label('Guardar cambios')->submit('save')->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord() ?? new Business;
        $record->fill($data);
        $record->save();

        Notification::make()->success()->title('Configuración guardada')->send();
    }

    public function getRecord(): ?Business
    {
        return Business::query()->first();
    }
}
