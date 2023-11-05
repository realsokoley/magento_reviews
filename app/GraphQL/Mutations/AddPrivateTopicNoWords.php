<?php

namespace App\GraphQL\Mutations;

use App\Models\PendingTask;
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

        if (PendingTask::where('user_id', $userId)->exists()) {
            throw ValidationException::withMessages(['error' => 'One pending task already exists for this user, wait untill it finishes']);
        }

        $pendingTask = new PendingTask();
        $pendingTask->user_id = $userId;
        $pendingTask->language = env('LANGUAGE');
        $pendingTask->status = 0;
        $pendingTask->save();

        $privateTopic = new PrivateTopic();
        $privateTopic->user_id = $userId;
        $privateTopic->topic = $args['topic'];
        $privateTopic->ai_words = 0;
        $privateTopic->state = 0;
        $privateTopic->description = '';
        $privateTopic->save();

        $user->day_limit = $user->day_limit-1;
        $user->month_limit = $user->month_limit-1;
        $user->save();

        return $privateTopic;
    }
}
