<?php

namespace App\Filament\Resources\CetakanNaikResource\Pages;

use App\Filament\Resources\CetakanNaikResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCetakanNaik extends CreateRecord
{
    protected static string $resource = CetakanNaikResource::class;
    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }
}
