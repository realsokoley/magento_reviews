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
        'meta_topic', 'description'
    ];

    /**
     * @return HasMany
     */
    public function topics()
    {
        return $this->hasMany('App\Models\Topic', 'meta_topic_id');
    }
}
