<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteMetaTopic extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metaTopic()
    {
        return $this->belongsTo('App\Models\MetaTopic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
