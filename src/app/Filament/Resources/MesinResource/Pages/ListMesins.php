<?php

namespace App\Filament\Resources\MesinResource\Pages;

use App\Filament\Resources\MesinResource;
use App\Filament\Resources\MesinResource\Widgets\MesinStatsOverview;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;

class ListMesins extends ListRecords
{
    protected static string $resource = MesinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string|Htmlable{
        return 'Mesin';
    }
    protected function getHeaderWidgets(): array
    {
        return [
            MesinStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Mesin')
                ->icon('heroicon-o-cog')
                ->iconPosition(IconPosition::After),
            'rusak' => Tab::make('Mesin Rusak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('keterangan', 'Rusak')),
            'tidak rusak' => Tab::make('Mesin Tidak Rusak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('keterangan', 'Tidak Rusak')),
        ];
    }
}
