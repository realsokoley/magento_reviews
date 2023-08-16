<?php

namespace App\GraphQL\Mutations;

use App\Models\PrivateTopic;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Validation\ValidationException;


class AddPrivateTopicNoWords
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userId = $context->user()->id;
        $user = User::find($userId);

        $dayLimit = $user->day_limit;
        $monthLimit = $user->month_limit;

        if ($dayLimit == 0) {
            throw ValidationException::withMessages(['error' => 'Day limit reached']);
        }

        if ($monthLimit == 0) {
            throw ValidationException::withMessages(['error' => 'Month limit reached']);
        }

        $privateTopic = new PrivateTopic();
        $privateTopic->user_id = $userId;
        $privateTopic->topic = $args['topic'];
        $privateTopic->ai_words = 0;
        $privateTopic->state = 0;
        $privateTopic->save();

        return $privateTopic;
    }
}
