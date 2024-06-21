<?php

namespace App\Filament\Resources\SandblastingResource\Pages;

use App\Filament\Resources\SandblastingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSandblasting extends EditRecord
{
    protected static string $resource = SandblastingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }
}
