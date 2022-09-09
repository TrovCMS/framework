<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Tags\HasTags;
use Trov\Concerns\HasAuthor;
use Trov\Concerns\HasFeaturedImage;
use Trov\Concerns\HasMeta;
use Trov\Concerns\HasPublishedScope;
use Trov\Concerns\Sluggable;

class Post extends Model
{
    use HasPublishedScope;
    use Sluggable;
    use HasFactory;
    use HasTags;
    use HasMeta;
    use HasAuthor;
    use SoftDeletes;
    use HasFeaturedImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'status',
        'author_id',
        'content',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'indexable' => 'boolean',
        'published_at' => 'date',
        'content' => 'array',
    ];

    protected $appends = [
        'excerpt',
    ];

    protected $with = [
        'meta',
    ];

    public function getExcerptAttribute(): string
    {
        return Str::of(strip_tags($this->content[0]['blocks'][0]['data']['content']))->excerpt(null, ['radius' => 300]);
    }

    public function getPublicUrl()
    {
        return route('blog.show', $this);
    }
}
