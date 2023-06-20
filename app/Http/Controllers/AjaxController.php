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
            'email' => 'required',
            'phone' => 'required',
        ]);
        $emailOrUserName = request()->email;
        if (strpos($emailOrUserName, "@gmail.com") !== false) {
            $email = $emailOrUserName;
        } else {
            $email = $emailOrUserName . "@gmail.com";
        }
        $name = request()->name;
        $device_id = request()->device_id ?? '';
        $admin_id = request()->admin_id;
        $phone = request()->phone;
        $from_url = Crypt::decrypt(request()->from_url) ?? '';
        $message = request()->message;
        $password = request()->password;
        $user = User::where('email', $email)->count();

        $parts = explode('@', $email);
        $password = $parts[0];
        $password;
        $credentials = request()->only('password');
        $credentials['email'] = $email;
        if ($user > 0) {
            $credentials = request()->only('password');
            $credentials['email'] = $email;

            // updating the url
            $user = User::where('email', $email)->first();
            $conversation = Conversation::where('user_id', $user->id)->first();
            $conversation->admin_id = $admin_id;
            $conversation->from_url = $from_url;
            $conversation->device_id = $device_id;
            $conversation->save();
            // updating the url

            Auth::attempt($credentials);
            return response()->json($user);
        } else {
            $user = User::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => Hash::make($password),
                'show_password' => $password,
                'email_verified_at' => now()
            ]);
            $credentials = request()->only('password');
            $credentials['email'] = $email;
            Auth::attempt($credentials);

            $auth = Auth::user();
            $conversation = new Conversation();
            $conversation->user_id = $auth->id;
            $conversation->admin_id = $admin_id;
            $conversation->device_id = $device_id;
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

    public function getNewMessage()
    {
        $totalMessage = Message::where('user_id', Auth::user()->id)->count();
        $data = Message::where('user_id', Auth::user()->id)->latest()->first();
        return response()->json([$data, $totalMessage]);
    }
}
