<?php

namespace App\Filament\Resources\CetakanResource\Pages;

use App\Filament\Resources\CetakanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCetakan extends EditRecord
{
    protected static string $resource = CetakanResource::class;

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
