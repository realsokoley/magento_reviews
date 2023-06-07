<?php

namespace App\GraphQL\Mutations;

use App\Models\UserFavoriteMetaTopic;
use App\Models\WordListUser;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddMetaThemeToFavorites
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userFavoriteMetaTopic = UserFavoriteMetaTopic::where(
            [
                ['user_id', '=', $context->user()->id],
                ['meta_topic_id', '=', $args['meta_topic_id']]
            ]
        )->first();

        if (!$userFavoriteMetaTopic) {
            $userFavoriteMetaTopic = new UserFavoriteMetaTopic();
            $userFavoriteMetaTopic->meta_topic_id = $args['meta_topic_id'];
            $userFavoriteMetaTopic->user_id = $context->user()->id;

            $userFavoriteMetaTopic->save();
        }

        return $userFavoriteMetaTopic;
    }
}
