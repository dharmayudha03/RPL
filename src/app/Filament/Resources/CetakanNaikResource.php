<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CetakanNaikResource\Pages;
use App\Filament\Resources\CetakanNaikResource\RelationManagers;
use App\Models\Cetakan;
use App\Models\CetakanNaik;
use App\Models\Mesin;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
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
use Illuminate\Support\Facades\Auth;

class CetakanNaikResource extends Resource
{
    protected static ?string $model = CetakanNaik::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Maintenance';
    protected static ?string $navigationLabel = 'Cetakan Naik';
    protected static ?string $slug = 'cetakan-naik';
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
                        Select::make('shift')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                'NS' => 'NS',
                            ])
                            ->native(false)
                            ->required(),
                        TimePicker::make('jam')
                            ->placeholder('hh:mm:ss'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->autocomplete(false),
                        Select::make('nomesin')
                            ->label('No. Mesin')
                            ->options(Mesin::all()->pluck('no_mesin', 'no_mesin'))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required(),
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
                        Section::make('Cetakan')
                        ->description('Deskripsi cetakan')
                            ->schema([
                                Select::make('codeitem')
                                    ->label('Code Item')
                                    ->native(false)
                                    ->options(Cetakan::all()->pluck('codeitem', 'codeitem'))
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                                TextInput::make('set')
                                    ->label('Mold Set')
                                    ->required()
                                    ->autocomplete(false),
                                TextInput::make('jumlah_cavity')
                                    ->label('Jumlah Cavity')
                                    ->numeric()
                                    ->required()
                            ])->columns(3),
                        Section::make('Point Check')
                        ->description('Pilih option dibawah untuk mengechek visual cetakan')
                            ->schema([
                                Radio::make('cavity')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('guidepen')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->label('Guide Pen')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('busing')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('baut-mur')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->label('Baut/Mur')
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('core')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('piston')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('pot')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                                Radio::make('pl')
                                    ->options([
                                        '√' => '√',
                                        '-' => '-',
                                    ])
                                    ->label('PL')
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->required(),
                            ])->columns(4),
                        Select::make('kesimpulan')
                            ->options([
                                'OK' => 'OK',
                                'NG' => 'NG',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('shift'),
                TextColumn::make('nomesin')
                    ->label('No. Mesin'),
                TextColumn::make('codeitem')
                    ->label('Code Item')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('set')
                    ->label('Mold Set'),
                TextColumn::make('kesimpulan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'OK' => 'success',
                        'NG' => 'danger',
                    }),
                TextColumn::make('cavity')
                    ->label('Cavity')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('guidepen')
                    ->label('Guide Pen')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('busing')
                    ->label('Busing')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('buat-mur')
                    ->label('Baut/Mur')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('core')
                    ->label('Core')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('piston')
                    ->label('Piston')
                    ->toggleable(isToggledHiddenByDefault: true), 
                TextColumn::make('pot')
                    ->label('Pot')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pl')
                    ->label('PL')
                    ->toggleable(isToggledHiddenByDefault: true),       
                TextColumn::make('rak')
                    ->label('Rak'),
                TextColumn::make('rak')
                    ->label('Rak'),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCetakanNaiks::route('/'),
            'create' => Pages\CreateCetakanNaik::route('/create'),
            'edit' => Pages\EditCetakanNaik::route('/{record}/edit'),
        ];
    }
    
}


