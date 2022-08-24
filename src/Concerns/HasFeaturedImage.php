<?php

namespace Trov\Concerns;

trait HasFeaturedImage
{
    public function getFeaturedImageAttribute()
    {
        return $this->meta && $this->meta->ogImage ? $this->meta->ogImage : (object) config('trov.default_featured_image');
    }
}
