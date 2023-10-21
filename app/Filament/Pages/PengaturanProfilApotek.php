<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Apoteker;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use App\Settings\ProfileApotekSettings;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengaturanProfilApotek extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = ProfileApotekSettings::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Pengaturan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasPermissionTo('settings: apotek-profile');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('settings: apotek-profile'), 403);
        
        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_apotek')
                            ->label('Nama Apotek')
                            ->required(),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('no_telp')
                                    ->label('No. Telp')
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ]),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(3),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi'),
                                Forms\Components\TextInput::make('kabupaten_kota')
                                    ->label('Kabupaten/Kota'),
                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan'),
                                Forms\Components\TextInput::make('kelurahan_desa')
                                    ->label('Kelurahan/Desa'),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\FileUpload::make('logo')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str($file->guessExtension())
                                            ->prepend('logo.'),
                                    )
                                    ->image()
                                    ->disk('files-apotek')
                                    ->directory('apotek')
                                    ->visibility('public'),
                            ]),
                        Forms\Components\TextInput::make('slogan'),
                        Forms\Components\Grid::make()
                            ->columns([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('sia_no')
                                    ->label('No. SIA'),
                                Forms\Components\DatePicker::make('tgl_exp_sia')
                                    ->label('Tanggal Kadaluarsa SIA')
                                    ->format('d/m/Y')
                                    ->minDate(now()),
                                Forms\Components\FileUpload::make('berkas_sia')
                                    ->label('Berkas SIA')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (Forms\Get $get): string => "sia-".$get('stra_no').".pdf",
                                    )
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (Forms\Get $get, TemporaryUploadedFile $file): string => (string) str($file->guessExtension())
                                            ->prepend('berkas-sia-no.'.$get('sia_no').'.'),
                                    )
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->downloadable()
                                    ->disk('files-apotek')
                                    ->directory('apoteker')
                                    ->hint('Format berkas *.pdf'),
                            ]),                       
                        Forms\Components\Select::make('apoteker_id')
                            ->label('Apoteker Utama')
                            ->options(
                                Apoteker::all()->pluck('nama', 'id')
                            ),
                    ]),
                
            ]);
    }
}
