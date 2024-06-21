<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CetakanResource\Pages;
use App\Filament\Resources\CetakanResource\RelationManagers;
use App\Filament\Resources\CetakanResource\Widgets\CetakanStatsOverview;
use App\Filament\Resources\CetakanResource\Widgets\StatsOverview;
use App\Models\Cetakan;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Filament\Forms\Components\Number;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CetakanResource extends Resource
{
    protected static ?string $model = Cetakan::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    
    protected static ?string $navigationLabel = 'Cetakan';

    protected static ?string $navigationGroup = 'List Cetakan dan Mesin';
    protected static ?string $slug = 'cetakan';
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        // Periksa apakah pengguna memiliki salah satu dari peran berikut: 'user' atau 'admin'
        if ($user && ($user->hasRole('user') || $user->hasRole('admin'))) {
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
                        Section::make('Cetakan')
                        ->description('Deskripsi Cetakan')
                            ->schema([
                                TextInput::make('codeitem')
                                    ->label('Code Item')
                                    ->required()
                                    ->maxLength(15)
                                    ->rule('regex:/^[A-Z0-9]*$/')
                                    ->maxLength(13)
                                    ->autocomplete(false),
                                TextInput::make('partnumber')
                                    ->label('Part Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->autocomplete(false),
                                TextInput::make('partname')
                                    ->label('Part Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->autocomplete(false),
                                TextInput::make('set')
                                    ->label('Set')
                                    ->required()
                                    ->maxLength(3)
                                    ->autocomplete(false),
                                TextInput::make('cavity')
                                    ->label('Cavity')
                                    ->required()
                                    ->numeric()
                                    ->autocomplete(false),
                            ])->columns(3),
                        Section::make('Penyimpanan Cetakan')
                            ->schema([
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
                                Select::make('norak')
                                    ->options([
                                        '1' => '1',
                                        '2' => '2',
                                        '3' => '3',
                                        '4' => '4',
                                        '5' => '5',
                                        '6' => '1',
                                        '7' => '1',
                                        '8' => '1',
                                        '9' => '1',
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
                                        '24' => '24'
                                    ])
                                    ->searchable()
                                    ->label('No. Rak')
                                    ->native(false),
                                Select::make('posisi_tooling')
                                    ->options([
                                        'Plant 1' => 'Plant 1',
                                        'Plant 2' => 'Plant 2',
                                        'Plant 4' => 'Plant 4',
                                        'DSM' => 'DSM',
                                    ])
                                    ->native(false),
                            ])->columns(3),
                        Select::make('keterangan')
                            ->options([
                                'Rusak' => 'Rusak',
                                'Tidak Rusak' => 'Tidak Rusak',
                            ])
                            ->columnSpanFull()
                            ->native(false),
                    ])->columns(2)
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('codeitem')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('partname')
                    ->label('Part Name'),
                TextColumn::make('partnumber')
                    ->label('Part Number'),
                TextColumn::make('set'),
                TextColumn::make('cavity'),
                TextColumn::make('rak')
                    ->searchable(),
                TextColumn::make('norak')
                    ->searchable(),
                TextColumn::make('posisi_tooling')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Tidak Aktif' => 'danger',
                    })
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
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
                Filter::make('Tidak Aktif')
                    ->query(fn (Builder $query): Builder => $query->where('keterangan', 'Tidak Aktif')),
                Filter::make('Aktif')
                    ->query(fn (Builder $query): Builder => $query->where('keterangan', 'Aktif')),
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
    public static function getWidgets(): array
    {
        return[
            CetakanStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCetakans::route('/'),
            'create' => Pages\CreateCetakan::route('/create'),
            'edit' => Pages\EditCetakan::route('/{record}/edit'),
        ];
    }
}
