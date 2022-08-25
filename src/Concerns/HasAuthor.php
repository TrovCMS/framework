<?php

namespace Trov\Concerns;

use App\Models\User;

trait HasAuthor
{
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
