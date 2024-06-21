<?php

namespace App\Filament\Resources\SandblastingResource\Pages;

use App\Filament\Resources\SandblastingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSandblasting extends CreateRecord
{
    protected static string $resource = SandblastingResource::class;
    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }
}
