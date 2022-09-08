<?php

namespace Trov\Forms\Actions;

use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PreviewAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'previewPublicUrl';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Preview'));

        $this->color('secondary');

        $this->groupedIcon('heroicon-s-eye');

        $this->url(fn (Model $record): string => $record->getPublicUrl());

        $this->openUrlInNewTab();

        $this->hidden(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });
    }
}
