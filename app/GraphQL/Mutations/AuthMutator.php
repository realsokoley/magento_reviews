<?php

namespace App\GraphQL\Mutations;

use App\Models\WordList;
use App\Models\WordListUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthMutator
{
    private array $wordLists = [];

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $credentials = Arr::only($args, ['email', 'password']);

        if (Auth::once($credentials)) {
            $token = Str::random(60);

            $user = auth()->user();
            $user->api_token = $token;
            $user->paid = 0;
            $user->active = 1;
            $user->day_limit = 2;
            $user->month_limit = 10;
            $user->save();

            $userData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'token' => $user->api_token
            ];

            return \json_encode($userData);
        }

        return null;
    }

    public function getWordLists(): array
    {
        if ($this->wordLists) {
            return $this->wordLists;
        }

        $this->wordLists = WordList::whereNotNull('list')->get()->toArray();
        return $this->wordLists;
    }
}
