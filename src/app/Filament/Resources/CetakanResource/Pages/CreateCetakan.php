<?php

namespace App\Filament\Resources\CetakanResource\Pages;

use App\Filament\Resources\CetakanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCetakan extends CreateRecord
{
    protected static string $resource = CetakanResource::class;
    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }
}
