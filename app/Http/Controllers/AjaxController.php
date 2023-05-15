<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function loginOrAccount()
    {
        $name = request()->name;
        $email = request()->email;
        $message = request()->message;
        $password = request()->password;
        $user = User::where('email', $email)->count();

        $parts = explode('@', $email);
        $password = $parts[0];
        $password;

        if ($user > 0) {
            $credentials = request()->only('email', 'password');
            $auth = Auth::attempt($credentials);
            $sight = 'login';
            return response()->json([$user, $sight]);
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'password' => Hash::make($password),
                'email_verified_at' => now()
            ]);
            $credentials = request()->only('email', 'password');
            Auth::attempt($credentials);
            $sight = 'register';
            return response()->json([$user, $sight]);
        }
    }
}
