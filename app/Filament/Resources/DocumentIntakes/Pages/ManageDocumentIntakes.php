<?php

namespace App\Filament\Resources\DocumentIntakes\Pages;

use App\Filament\Resources\DocumentIntakes\DocumentIntakeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDocumentIntakes extends ManageRecords
{
    protected static string $resource = DocumentIntakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
