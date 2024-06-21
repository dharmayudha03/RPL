<?php

namespace App\Filament\Resources\CetakanNaikResource\Pages;

use App\Filament\Resources\CetakanNaikResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCetakanNaik extends EditRecord
{
    protected static string $resource = CetakanNaikResource::class;

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
