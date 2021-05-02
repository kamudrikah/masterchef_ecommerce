<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $user = User::first();
        Notification::send($user, new NewUser());
        return view('landing');
    }
}
