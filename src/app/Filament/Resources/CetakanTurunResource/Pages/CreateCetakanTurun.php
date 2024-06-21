<?php

namespace App\Filament\Resources\CetakanTurunResource\Pages;

use App\Filament\Resources\CetakanTurunResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCetakanTurun extends CreateRecord
{
    protected static string $resource = CetakanTurunResource::class;
    protected function getRedirectUrl():string{
        return $this->getResource()::getUrl('index');
    }
}
