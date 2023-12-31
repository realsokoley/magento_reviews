<?php

namespace App\GraphQL\Queries;

use App\Models\MetaTopic;
use App\Models\Topic;
use App\Models\TopicLevel;
use App\Models\UserFavoriteMetaTopic;
use App\Models\WordList;
use App\Models\WordListUser;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MetaTopicsRatingFavorites extends MetaTopicsRating
{
    public function resolve($rootValue, array $args, GraphQLContext $context)
    {
        $favorites = [];
        $user = $context->user();
        $userFavorites = UserFavoriteMetaTopic::where('user_id', $user->id)->get()->toArray();
        foreach ($userFavorites as $favorite) {
            $favorites[] = $favorite['meta_topic_id'];
        }

        $metaTopics = MetaTopic::whereIn('id', $favorites)->get();
        $result = [];

        foreach ($metaTopics as $metaTopic) {
            $result[] = [
                'metaTopic' => $metaTopic,
                'rating' => $this->calculateValue($metaTopic, $user),
                'is_favorite' => $this->isFavorite($metaTopic, $user)
            ];
        }

        return $result;
    }

    private function calculateValue($metaTopic, $user)
    {
        $topics = Topic::where('meta_topic_id', $metaTopic->id)->get()->toArray();
        $maxRating = 0;
        $rating = 0;
        foreach ($topics as $topic) {
            $topicLevels = TopicLevel::where('topic_id', $topic['id'])->get()->toArray();
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
        }

        return $maxRating == 0 ? 0 : (int) ($rating * 100 / $maxRating);
    }
}
