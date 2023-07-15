<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateTopic extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic', 'description','ai_words','state'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topicLevels()
    {
        return $this->hasMany('App\Models\TopicLevel', 'topic_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metaTopic()
    {
        return $this->belongsTo('App\Models\MetaTopic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userFavoriteTopics()
    {
        return $this->hasMany('App\Models\UserFavoriteTopic', 'topic_id');
    }
}
