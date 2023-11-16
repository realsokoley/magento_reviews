<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_sku', 'user_id', 'review'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
