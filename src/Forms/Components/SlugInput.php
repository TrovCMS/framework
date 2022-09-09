<?php

namespace Trov\Forms\Components;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class SlugInput extends TextInput
{
    protected string $view = 'trov::components.slug-input';

    public function getRouteName(): string
    {
        if ($this->isFrontPage()) {
            return config('app.url');
        }

        $record = $this->getRecord();

        return $record ? Str::of($record->getPublicUrl())->replace($record->slug, '') : '/';
    }

    public function isFrontPage()
    {
        return is_front_page($this->getRecord());
    }
}
