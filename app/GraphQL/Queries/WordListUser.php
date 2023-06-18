<?php

namespace App\GraphQL\Queries;

use App\Models\WordListUser as ModelWordListUser;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class WordListUser
{
    public function resolve($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();
        $wlus = ModelWordListUser::where(
            [
                ['word_list_id', '=', $args['word_list_id']],
                ['user_id', '=', $user->id]
            ]
        )->get()->toArray();

        return $wlus;
    }


}
