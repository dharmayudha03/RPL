<?php

namespace App\Filament\Resources\CetakanResource\Pages;

use App\Filament\Resources\CetakanResource;
use App\Filament\Resources\CetakanResource\Widgets\CetakanStatsOverview;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;

class ListCetakans extends ListRecords
{
    protected static string $resource = CetakanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string|Htmlable{
        return 'Cetakan';
    }
    protected function getHeaderWidgets(): array
    {
        return [
            CetakanStatsOverview::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Cetakan')
                ->icon('heroicon-o-square-3-stack-3d')
                ->iconPosition(IconPosition::After),
            'Aktif' => Tab::make('Cetakan Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('keterangan', 'Aktif')),
            'Tidak Aktif' => Tab::make('Cetakan Tidak Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('keterangan', 'Tidak Aktif')),
        ];
    }
}
