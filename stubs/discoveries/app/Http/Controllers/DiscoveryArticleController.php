<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryArticle;

class DiscoveryArticleController extends Controller
{
    public function show(DiscoveryArticle $article)
    {
        abort_unless($article->status == 'published' || auth()->user(), 404);

        return view('discoveries.article', [
            'article' => $article,
        ]);
    }
}
