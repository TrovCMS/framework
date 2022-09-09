<?php

namespace Trov\Forms\Components;

use Closure;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TitleWithSlug
{
    public static function make(string $titleField, string $slugField): Group
    {
        return Group::make()
            ->schema([
                TextInput::make($titleField)
                    ->required()
                    ->reactive()
                    ->disableLabel()
                    ->placeholder(fn () => Str::title($titleField))
                    ->extraInputAttributes(['class' => 'text-2xl'])
                    ->afterStateUpdated(function (Closure $set, $context, $state) use ($slugField) {
                        if ($context === 'create') {
                            return $set($slugField, Str::slug($state));
                        }
                    }),
                SlugInput::make($slugField)
                    ->required()
                    ->disableLabel()
                    ->unique(ignorable: fn (?Model $record) => $record),
            ]);
    }
}
