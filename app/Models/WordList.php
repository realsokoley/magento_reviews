<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordList extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'word_list',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topicLevels()
    {
        return $this->hasMany('App\Models\TopicLevel', 'word_list_id');
    }
}
