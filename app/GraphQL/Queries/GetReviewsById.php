<?php

namespace App\GraphQL\Queries;

use App\Models\Review;

class GetReviewsById
{
    public function resolve($rootValue, array $args)
    {
        return Review::where('product_id', $args['productId'])->get();
    }
}
