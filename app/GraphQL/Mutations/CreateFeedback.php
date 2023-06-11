<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Feedback;
use Illuminate\Support\Facades\Mail;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateFeedback
{
    public function resolve($rootValue, array $args, GraphQLContext $context)
    {
        $feedback = new Feedback;
        $feedback->email = $args['email'];
        $feedback->message = $args['message'];
        $feedback->user_id = $context->user()->id;
        $feedback->save();

        Mail::raw($args['message'], function ($message) {
            $message->to('sokoley1993@gmail.com')
                ->subject('Customer Feedback');
        });

        return $feedback;
    }
}
