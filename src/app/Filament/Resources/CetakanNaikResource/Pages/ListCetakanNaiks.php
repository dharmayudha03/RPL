<?php

namespace App\Filament\Resources\CetakanNaikResource\Pages;

use App\Filament\Resources\CetakanNaikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListCetakanNaiks extends ListRecords
{
    protected static string $resource = CetakanNaikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string|Htmlable{
        return 'Cetakan Naik';
    }
}
