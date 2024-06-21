<?php

namespace App\Filament\Resources\SandblastingResource\Pages;

use App\Filament\Resources\SandblastingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListSandblastings extends ListRecords
{
    protected static string $resource = SandblastingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string|Htmlable{
        return 'Sandblasting';
    }

}
