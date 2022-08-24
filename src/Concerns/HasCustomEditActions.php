<?php

namespace Trov\Concerns;

use Filament\Pages\Actions;
use FilamentAddons\Forms\Actions\PreviewAction;

trait HasCustomEditActions
{
    public function getActions(): array
    {
        return [
            Actions\Action::make('save')->color('primary')->action('save'),
            PreviewAction::make()->record($this->record),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
