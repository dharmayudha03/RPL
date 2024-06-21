<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\View\LegacyComponents\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'User';
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        // Periksa apakah pengguna memiliki salah satu dari peran berikut: 'user' atau 'admin'
        if ($user && ($user->hasRole('admin') || $user->hasRole('user') || $user->hasRole('maintenance'))) {
            return true;
        } else {
            return false;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (Page $liveware): bool => $liveware instanceof CreateRecord),
                    Select::make('roles')->multiple()->relationship('roles', 'name'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('roles.name'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $currentUser = auth()->user(); // Mendapatkan pengguna yang sedang login

        if ($currentUser) {
            $currentUserRoles = $currentUser->roles()->pluck('name')->toArray(); // Mendapatkan peran-peran dari pengguna yang sedang login

            if (in_array('admin', $currentUserRoles)) {
                // Jika user memiliki peran admin, tampilkan admin, maintenance, dan user
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'maintenance', 'user']);
                })->pluck('id');
            } elseif (in_array('maintenance', $currentUserRoles)) {
                // Jika user memiliki peran maintenance, tampilkan hanya maintenance
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['maintenance']);
                })->pluck('id');
            } elseif (in_array('user', $currentUserRoles)) {
                // Jika user memiliki peran user, tampilkan hanya user
                $users = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['user']);
                })->pluck('id');
            } else {
                // Jika user tidak memiliki peran, tidak tampilkan data apapun
                return User::whereNull('id'); // Mengembalikan query kosong
            }

            // Mengembalikan query builder dari parent class (model terkait),
            // dan menambahkan kondisi WHERE IN untuk memfilter user yang sesuai dengan peran user yang sedang login
            return parent::getEloquentQuery()->whereIn('id', $users);
        }

        // Default: Kembalikan query builder dari parent class tanpa filter tambahan
        return parent::getEloquentQuery();
    }

}
