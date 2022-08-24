<?php

namespace Trov\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meta;

class SitemapController extends Controller
{
    public function index()
    {
        $links = Meta::where('indexable', true)->whereRelation('metaable', 'status', 'Published')->get();

        return response()->view('sitemap', [
            'links' => $links,
        ])->header('Content-Type', 'text/xml');
    }

    public function pretty()
    {
        $links = Meta::where('indexable', true)->whereRelation('metaable', 'status', 'Published')->get();

        return response()->view('pretty-sitemap', [
            'links' => $links,
        ]);
    }
}
