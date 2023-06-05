<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetaTopic extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meta_topic', 'description', 'image_url'
    ];

    /**
     * @return HasMany
     */
    public function topics()
    {
        return $this->hasMany('App\Models\Topic', 'meta_topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userFavoriteMetaTopics()
    {
        return $this->hasMany('App\Models\UserFavoriteMetaTopic', 'meta_topic_id');
    }
}
