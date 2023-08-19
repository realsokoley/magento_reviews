<?php

namespace App\GraphQL\Queries;

use App\Models\PrivateTopic;
use App\Models\PrivateTopicLevel;
use App\Models\UserFavoriteTopic;
use App\Models\WordList;
use App\Models\WordListUser;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PrivateTopicsRating
{
    public function resolve($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();
        $topics = PrivateTopic::where('user_id', $user->id)->get();
        $result = [];

        foreach ($topics as $topic) {
            $result[] = [
                'topic' => $topic,
                'rating' => $this->calculateValue($topic, $user),
            ];
        }

        return $result;
    }

    private function calculateValue($topic, $user)
    {
        $maxRating = 0;
        $rating = 0;
        $topicLevels = PrivateTopicLevel::where('topic_id', $topic->id)->get()->toArray();
        foreach ($topicLevels as $topicLevel) {
            $wordList = WordList::find($topicLevel['word_list_id']);
            $maxRating += (int)$wordList->max_rating;
            $wlus = WordListUser::where(
                [
                    ['word_list_id', '=', $wordList->id],
                    ['user_id', '=', $user->id]
                ]
            )->get()->toArray();
            $wordListUser = array_shift($wlus);

            if (isset($wordListUser['rating'])) {
                $rating += $wordListUser['rating'];
            }
        }

        return $maxRating == 0 ? 0 : (int) ($rating * 100 / $maxRating);
    }

    public function isFavorite($topic, $user)
    {
        $userFavorites = UserFavoriteTopic::where(
            [
                ['topic_id', "=", $topic->id],
                ['user_id', '=', $user->id]
            ]
        )->get()->toArray();

        if (count($userFavorites) > 0) {
            return true;
        }

        return false;
    }

}
