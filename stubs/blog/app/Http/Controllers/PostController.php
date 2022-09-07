<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Spatie\Tags\Tag;

class PostController extends Controller
{
    public function index()
    {
        $tags = Tag::getWithType('post');

        $posts = $tags->map(function ($tag) use ($tags) {
            $psts = Post::isPublished()->withAnyTags($tag, 'post')->get();

            if ($psts->isEmpty()) {
                $tags->forget($tag->getKey());

                return;
            } else {
                return collect([
                    'tag' => $tag,
                    'posts' => $psts,
                ]);
            }
        });

        $posts = $posts->filter(function ($item) {
            return $item !== null;
        })->values();

        return view('posts.index', [
            'posts' => $posts,
            'meta' => (object) [
                'title' => 'Frequently Asked Questions',
                'description' => 'A list of our frequently asked questions.',
                'robots' => 'index,follow',
                'ogImage' => null,
            ],
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->status == 'Published' || auth()->user(), 404);

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
