<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MesinResource\Pages;
use App\Filament\Resources\MesinResource\RelationManagers;
use App\Filament\Resources\MesinResource\Widgets\MesinStatsOverview;
use App\Filament\Resources\MesinResource\Widgets\StatsOverview;
use App\Models\Mesin;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\PostDec;

class MesinResource extends Resource
{
    protected static ?string $model = Mesin::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'List Cetakan dan Mesin';
    protected static ?string $navigationLabel = 'Mesin';
    protected static ?string $slug = 'mesin';
    
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
                        TextInput::make('name_mesin')
                        ->label('Nama Mesin')
                        ->required()
                        ->maxLength(255)
                        ->autocomplete(false)
                        ->regex('/^[A-Z0-9]+$/')
                        ->placeholder('Masukkan dengan huruf kapital')
                        ->columnSpanFull(),
                        TextInput::make('no_mesin')
                            ->label('No Mesin')
                            ->required()
                            ->maxLength(9)
                            ->autocomplete(false)
                            ->regex('/^[A-Z0-9]+$/')
                            ->placeholder('Masukkan dengan huruf kapital'),
                        Select::make('keterangan')
                            ->options([
                                'Rusak' => 'Rusak',
                                'Tidak Rusak' => 'Tidak Rusak',
                            ])
                            ->native(false),
                    ])->columns(2)
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name_mesin')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('no_mesin')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tidak Rusak' => 'success',
                        'Rusak' => 'danger',
                    })
                    ->label('Keterangan'),
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
                Filter::make('Rusak')
                    ->query(fn (Builder $query): Builder => $query->where('keterangan', 'Rusak')),
                Filter::make('Tidak Rusak')
                    ->query(fn (Builder $query): Builder => $query->where('keterangan', 'Tidak Rusak')),
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
            MesinStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMesins::route('/'),
            'create' => Pages\CreateMesin::route('/create'),
            'edit' => Pages\EditMesin::route('/{record}/edit'),
        ];
    }
}
