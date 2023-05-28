<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function loginOrAccount()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => ['required', 'email'],
            'phone' => 'required',
        ]);
        $name = request()->name;
        $phone = request()->phone;
        $email = request()->email;
        $from_url = Crypt::decrypt(request()->from_url);
        $message = request()->message;
        $password = request()->password;
        $user = User::where('email', $email)->count();

        $parts = explode('@', $email);
        $password = $parts[0];
        $password;

        if ($user > 0) {
            $credentials = request()->only('email', 'password');

            // updating the url
            $user = User::where('email', $email)->first();
            $conversation = Conversation::where('user_id', $user->id)->first();
            $conversation->from_url = $from_url;
            $conversation->save();
            // updating the url

            Auth::attempt($credentials);
            return response()->json($user);
        } else {
            $user = User::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
                'password' => Hash::make($password),
                'show_password' => $password,
                'email_verified_at' => now()
            ]);
            $credentials = request()->only('email', 'password');
            Auth::attempt($credentials);

            $auth = Auth::user();
            $conversation = new Conversation();
            $conversation->user_id = $auth->id;
            $conversation->name = $name;
            $conversation->from_url = $from_url;
            $conversation->save();

            $message = new Message();
            $message->user_id = $auth->id;
            $message->conversation_id = $conversation->id;
            $message->type = 'receive';
            $message->content = 'Hi their! How can i assist you today?';
            $message->save();

            return response()->json($user);
        }
    }

    public function getConversation()
    {
        $conversation = Conversation::with('messages')->where('user_id', Auth::user()->id)->latest()->first();
        $data = $conversation->messages;
        return response()->json($data);
    }
}
