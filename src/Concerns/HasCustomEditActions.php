<?php

namespace Trov\Concerns;

use Filament\Pages\Actions;
use Trov\Forms\Actions\PreviewAction;
use Trov\Forms\Actions\SaveAction;

trait HasCustomEditActions
{
    public function getActions(): array
    {
        return [
            SaveAction::make(),
            PreviewAction::make()->record($this->record),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
