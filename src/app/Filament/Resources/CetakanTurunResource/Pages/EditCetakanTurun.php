<?php

namespace App\Filament\Resources\CetakanTurunResource\Pages;

use App\Filament\Resources\CetakanTurunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCetakanTurun extends EditRecord
{
    protected static string $resource = CetakanTurunResource::class;

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
