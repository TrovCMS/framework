<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryTopic;

class DiscoveryCenterController extends Controller
{
    public function index()
    {
        return view('discoveries.index', [
            'topics' => DiscoveryTopic::orderBy('title')->where('status', 'Published')->get(),
            'meta' => (object) [
                'title' => 'Discovery Center',
                'description' => 'A curated list of things we find interesting.',
                'robots' => 'index,follow',
                'ogImage' => null,
            ],
        ]);
    }
}
