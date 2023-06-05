<?php

namespace App\GraphQL\Mutations;

use App\Models\UserFavoriteTopic;
use App\Models\WordListUser;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddThemeToFavorites
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userFavoriteTopic = UserFavoriteTopic::where(
            [
                ['user_id', '=', $context->user()->id],
                ['topic_id', '=', $args['topic_id']]
            ]
        )->first();

        if (!$userFavoriteTopic) {
            $userFavoriteTopic = new WordListUser();
            $userFavoriteTopic->topic_id = $args['topic_id'];
            $userFavoriteTopic->user_id = $context->user()->id;

            $userFavoriteTopic->save();
        }

        return $userFavoriteTopic;
    }
}
