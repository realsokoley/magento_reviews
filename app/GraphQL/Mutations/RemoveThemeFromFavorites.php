<?php

namespace App\GraphQL\Mutations;

use App\Models\UserFavoriteTopic;
use App\Models\WordListUser;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RemoveThemeFromFavorites
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userFavoriteTopic = UserFavoriteTopic::where(
            [
                ['user_id', '=', $context->user()->id],
                ['topic_id', '=', $args['topic_id']]
            ]
        )->first();

        $userFavoriteTopic?->delete();

        return 'ok';
    }
}
