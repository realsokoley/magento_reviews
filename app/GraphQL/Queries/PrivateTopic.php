<?php

namespace App\GraphQL\Queries;

use App\Models\PrivateTopic as PrivateTopicModel;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PrivateTopic
{
    public function resolve($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();
        $topic = PrivateTopicModel::find($args['id']);
        if ($topic->user->id != $user->id) {
            throw ValidationException::withMessages(['error' => 'Not allowed for current user']);
        }

        return $topic;
    }
}
