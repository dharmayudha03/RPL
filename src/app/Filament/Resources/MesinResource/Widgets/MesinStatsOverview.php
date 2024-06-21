<?php

namespace App\Filament\Resources\MesinResource\Widgets;

use App\Models\Mesin;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MesinStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Mesin', Mesin::all('name_mesin')->count()),
            Stat::make('Jumlah Mesin Rusak', Mesin::where('keterangan', 'Rusak')->count()),
            Stat::make('Jumlah Mesin Tidak Rusak', Mesin::where('keterangan', 'Tidak Rusak')->count()),
        ];
    }
}
