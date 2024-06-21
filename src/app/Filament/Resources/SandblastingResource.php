<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodeitemResource\RelationManagers\SandblastingRelationManager;
use App\Filament\Resources\SandblastingResource\Pages;
use App\Filament\Resources\SandblastingResource\RelationManagers;
use App\Models\Cetakan;
use App\Models\Mesin;
use App\Models\Sandblasting;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SandblastingResource extends Resource
{
    protected static ?string $model = Sandblasting::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Maintenance';
    protected static ?string $navigationLabel = 'Sandblasting';
    protected static ?string $slug = 'sandblasting';
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        // Periksa apakah pengguna memiliki salah satu dari peran berikut: 'user' atau 'admin'
        if ($user && ($user->hasRole('user') || $user->hasRole('admin') || $user->hasRole('maintenance'))) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Card::make()
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama')
                            ->maxLength(255)
                            ->required()
                            ->autocomplete(false),
                        Select::make('shift')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                'NS' => 'NS',
                            ])
                            ->native(false)
                            ->required(),
                        Select::make('nomesin')
                            ->label('NO. Mesin')
                            ->options(Mesin::all()->pluck('no_mesin', 'no_mesin'))
                            ->preload()
                            ->native(false)
                            ->searchable()
                            ->required(),
                        Select::make('codeitem')
                            ->label('Code Item')
                            ->options(Cetakan::all()->pluck('codeitem', 'codeitem'))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required(),
                        TextInput::make('set')
                            ->label('Set')
                            ->regex('/^[A-Z0-9]+$/')
                            ->required()
                            ->maxLength(3)
                            ->autocomplete(false),
                        TextInput::make('cavity_ok')
                            ->label('Cavity OK')
                            ->numeric()
                            ->required(),
                        TextInput::make('cavity_ng')
                            ->label('Cavity NG')
                            ->numeric()
                            ->required(),
                        Section::make('Point Check')
                        ->description('Pilih option untuk metode cleaning cetakan')
                            ->schema([
                                Radio::make('sandblasting')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('cuci')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('autosol')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('gerinda')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('oiling')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                            ])->columns(5),
                        Section::make('Keterangan')
                            ->schema([
                                TimePicker::make('cetakan_naik')
                                    ->placeholder('hh:mm:ss'),
                                TimePicker::make('cetakan_turun')
                                    ->placeholder('hh:mm:ss'),
                            ])->columns(2),
                        Select::make('rak')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                '8' => '8',
                                '9' => '9',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                                '13' => '13',
                                '14' => '14',
                                '15' => '15',
                                '16' => '16',
                                '17' => '17',
                                '18' => '18',
                                '19' => '19',
                                '20' => '20',
                                '21' => '21',
                                '22' => '22',
                                '23' => '23',
                                '24' => '24',
                                '25' => '25',
                                '26' => '26',
                            ])
                            ->native(false),
                        TextInput::make('mengetahui_spv')
                            ->label('Mengetahui Supervisor')
                            ->maxLength(255)
                            ->autocomplete(false),
                    ])->columns(2)
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('tanggal')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('shift'),
                TextColumn::make('nomesin')
                    ->label('No. Mesin'),
                TextColumn::make('codeitem')
                    ->searchable()
                    ->label('Code Item')
                    ->sortable(),
                TextColumn::make('set')
                    ->label('Set'),
                TextColumn::make('cavity_ok')
                    ->label('Cavity OK'),
                TextColumn::make('cavity_ng')
                    ->label('Cavity NG'),
                TextColumn::make('rak')
                    ->label('Rak'),
                TextColumn::make('mengetahui_spv')
                    ->label('Mengetahui SPV'),
                TextColumn::make('sandblasting')
                    ->label('Sandblasting')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cuci')
                    ->label('Cuci')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('autosol')
                    ->label('Autosol')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gerinda')
                    ->label('Gerinda')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('oiling')
                    ->label('Oiling')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSandblastings::route('/'),
            'create' => Pages\CreateSandblasting::route('/create'),
            'edit' => Pages\EditSandblasting::route('/{record}/edit'),
        ];
    }
}
