<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::isPublished()->latest()->paginate(15);

        return view('blog.index', [
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

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
