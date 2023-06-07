<?php

namespace App\GraphQL\Mutations;

use App\Models\UserFavoriteMetaTopic;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RemoveMetaThemeFromFavorites
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        Log::info('User ID: '.$context->user()->id);
        Log::info('Meta Topic ID: '.$args['meta_topic_id']);

        $userFavoriteTopic = UserFavoriteMetaTopic::where(
            [
                ['user_id', '=', $context->user()->id],
                ['meta_topic_id', '=', $args['meta_topic_id']]
            ]
        )->first();

        Log::info('User Favorite Meta Topic: '.($userFavoriteTopic ? 'Found' : 'Not Found'));

        if ($userFavoriteTopic) {
            $userFavoriteTopic->delete();
            Log::info('User Favorite Meta Topic deleted.');
        } else {
            Log::info('User Favorite Meta Topic not deleted because it was not found.');
        }
    }
}
