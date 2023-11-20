<?php

namespace App\GraphQL\Queries;

use App\Models\Review;

class GetReviewsBySku
{
    public function resolve($rootValue, array $args)
    {
        return Review::where('product_sku', $args['productSku'])->get();
    }
}
