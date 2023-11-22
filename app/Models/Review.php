<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_sku', 'product_id', 'user_id', 'review', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
