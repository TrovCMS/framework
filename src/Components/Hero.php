<?php

namespace Trov\Components;

use Closure;
use Filament\Forms;
use FilamentAddons\Forms as AddonForms;
use FilamentCurator\Forms as CuratorForms;

class Hero
{
    public static function make(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Hero')
            ->schema([
                Forms\Components\Radio::make('hero.type')
                    ->inline()
                    ->default('image')
                    ->options([
                        'image' => 'Image',
                        'oembed' => 'oEmbed',
                    ])
                    ->reactive(),
                CuratorForms\Components\MediaPicker::make('hero.image')
                    ->label('Image')
                    ->visible(fn (Closure $get): bool => $get('hero.type') == 'image' ?: false),
                AddonForms\Components\OEmbed::make('hero.oembed')
                    ->label('Details')
                    ->visible(fn (Closure $get): bool => $get('hero.type') == 'oembed' ?: false),
                Forms\Components\Textarea::make('hero.cta')
                    ->label('Call to Action')
                    ->rows(3),
            ]);
    }
}
