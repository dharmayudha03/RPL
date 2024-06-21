<?php

namespace App\Filament\Resources\CetakanTurunResource\Pages;

use App\Filament\Resources\CetakanTurunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListCetakanTuruns extends ListRecords
{
    protected static string $resource = CetakanTurunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string|Htmlable{
        return 'Cetakan Turun';
    }
}
