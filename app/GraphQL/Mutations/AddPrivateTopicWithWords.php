<?php

namespace App\GraphQL\Mutations;

use App\Models\PrivateTopic;
use App\Models\PrivateTopicLevel;
use App\Models\User;
use App\Models\WordList;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Validation\ValidationException;


class AddPrivateTopicWithWords
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

        $array = json_decode($args['words'], true);
        if (!is_array($array)) {
            throw ValidationException::withMessages(['error' => 'Json decoding error']);
        }

        if (!$this->validateArray($array)) {
            throw ValidationException::withMessages(['error' => 'Validation words not passed']);
        }

        $wordList = new WordList();
        $wordList->list = $args['words'];
        $wordList->max_rating = 12;
        $wordList->save();

        $privateTopic = new PrivateTopic();
        $privateTopic->user_id = $userId;
        $privateTopic->topic = $args['topic'];
        $privateTopic->ai_words = 1;
        $privateTopic->state = 2;
        $privateTopic->save();

        $privateTopicLevel = new PrivateTopicLevel();
        $privateTopicLevel->topic_id = $privateTopic->id;
        $privateTopicLevel->level_id = 1;
        $privateTopicLevel->word_list_id = $wordList->id;
        $privateTopicLevel->save();

        return $privateTopic;
    }

    public function validateArray($array): bool
    {

        foreach ($array as $levelWord) {
            if (!isset($levelWord['word']) || !isset($levelWord['translation'])) {
                return false;
            }
        }

        return true;
    }

}
