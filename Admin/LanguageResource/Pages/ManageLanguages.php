<?php

namespace Modules\Language\Admin\LanguageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Modules\Language\Admin\LanguageResource;

class ManageLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
