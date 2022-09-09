<?php

namespace Trov\Components;

use Filament\Forms;
use FilamentCurator\Forms as CuratorForms;
use Illuminate\Support\Str;

class Meta
{
    public static function make(): Forms\Components\Section
    {
        return Forms\Components\Section::make('SEO')
            ->collapsible()
            ->relationship('meta')
            ->saveRelationshipsUsing(function ($component, $state) {
                $record = $component->getCachedExistingRecord();
                $state['og_image'] = isset($state['og_image']) ? $state['og_image'] : null;
                if ($record) {
                    $record->update($state);

                    return;
                }

                $component->getRelationship()->create($state);
            })
            ->columns(['md' => 2])
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->helperText(function (?string $state): string {
                            return Str::of(strlen($state))
                                ->append(' / ')
                                ->append(60 . ' ')
                                ->append(str('characters')->lower())
                                ->value();
                        })
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->helperText(function (?string $state): string {
                            return Str::of(strlen($state))
                                ->append(' / ')
                                ->append(160 . ' ')
                                ->append(str('characters')->lower())
                                ->value();
                        })
                        ->reactive()
                        ->required(),
                    Forms\Components\Toggle::make('indexable')
                        ->label('Indexable'),
                ]),
                CuratorForms\Components\MediaPicker::make('og_image')
                    ->label('OG Image')
                    ->helperText('Leave empty to use default. This will also be used on any resources that utilizes a featured image i.e. blog posts.'),
            ]);
    }
}
