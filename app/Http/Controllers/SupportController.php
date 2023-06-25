<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index()
    {
        return view('support.index');
    }

    public function submit(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $text = $request->input('message');

        $text = $name . '. ' . $email . '. ' . $text;

        Mail::raw($text, function ($message) {
            $message->to('sokoley1993@gmail.com')
                ->subject('Support Question');
        });

        return redirect('/support')->with('success', 'Your message has been sent. We will respond as soon as possible.');
    }

}
