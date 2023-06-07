<?php

namespace App\GraphQL\Mutations;

use App\Models\UserFavoriteMetaTopic;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RemoveMetaThemeFromFavorites
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userFavoriteTopic = UserFavoriteMetaTopic::where(
            [
                ['user_id', '=', $context->user()->id],
                ['meta_topic_id', '=', $args['meta_topic_id']]
            ]
        )->first();

        $userFavoriteTopic?->delete();

        return 'ok';
    }
}
