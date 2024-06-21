<?php

namespace App\Filament\Resources\CetakanResource\Widgets;

use App\Models\Cetakan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CetakanStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Cetakan', Cetakan::all('codeitem')->count()),
            Stat::make('Jumlah Cetakan Tidak Aktif', Cetakan::where('keterangan', 'Tidak Aktif')->count()),
            Stat::make('Jumlah Cetakan Aktif', Cetakan::where('keterangan', 'Aktif')->count()),
        ];
    }
}
