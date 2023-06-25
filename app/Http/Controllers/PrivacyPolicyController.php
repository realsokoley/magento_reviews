<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        return view('privacy_policy.index');
    }
}
