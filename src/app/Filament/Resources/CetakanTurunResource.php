<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CetakanTurunResource\Pages;
use App\Filament\Resources\CetakanTurunResource\RelationManagers;
use App\Models\Cetakan;
use App\Models\CetakanTurun;
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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CetakanTurunResource extends Resource
{
    protected static ?string $model = CetakanTurun::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Cetakan Turun';
    protected static ?string $navigationGroup = 'Maintenance';
    protected static ?string $slug = 'cetakan-turun';
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
                            ->native(false)
                            ->options(Mesin::all()->pluck('no_mesin', 'no_mesin'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('rak')
                            ->numeric()
                            ->required()
                            ->autocomplete(false),
                        Section::make('Cetakan')
                        ->description('Deskripsi Cetakan')
                            ->schema([
                                Select::make('codeitem')
                                    ->label('Code Item')
                                    ->options(Cetakan::all()->pluck('codeitem', 'codeitem'))
                                    ->native(false)
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('set')
                                    ->label('Mold Set')
                                    ->required()
                                    ->autocomplete(false),
                                TextInput::make('jumlah_cavity')
                                    ->label('Jumlah Cavity')
                                    ->numeric()
                                    ->required(),
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
                TextColumn::make('jumlah_cavity')
                    ->label('Jumlah Cavity'),
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
                TextColumn::make('kesimpulan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'OK' => 'success',
                        'NG' => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCetakanTuruns::route('/'),
            'create' => Pages\CreateCetakanTurun::route('/create'),
            'edit' => Pages\EditCetakanTurun::route('/{record}/edit'),
        ];
    }
}
