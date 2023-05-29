<?php

namespace App\GraphQL\Mutations;

use App\Models\WordListUser;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AdjustUserWordListResult
{

    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $wordListUser = WordListUser::where(
            [
                ['user_id', '=', $context->user()->id],
                ['word_list_id', '=', $args['word_list_id']]
            ]
        )->first();

        $wordListUser->rating = $args['rating'];
        $wordListUser->save();

        return $wordListUser;
    }
}
