<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function index()
    {
        $admin_id = $_SERVER['QUERY_STRING'];
        $data = Conversation::where('admin_id', $admin_id)->with('messages')->latest()->get();
        return response()->json($data);
    }

    public function getUserById($id)
    {
        $data = Conversation::where('user_id', $id)->with('messages')->latest()->get();
        return response()->json($data);
    }

    public function send(Request $request)
    {
        $text = $request->content;
        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = User::findOrFail($conversation->user_id);
        $data = Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'type' => 'receive',
            'content' => $text,
            'status' => 'seen',
        ]);
        $data->device_id = $conversation->device_id;
        return response()->json($data);
    }

    public function sendUser(Request $request)
    {
        $text = $request->content;
        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = User::findOrFail($conversation->user_id);
        $data = new Message();
        $data = Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'type' => 'send',
            'site' => $conversation->from_url,
            'content' => $text,
        ]);
        $data->device_id = $conversation->device_id;

        $conversation->status = 'receive';
        $conversation->updated_at = now();
        $conversation->save();
        return response()->json($data);
    }

    public function loginOrAccount(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => ['required', 'email'],
            'phone' => 'required',
        ]);
        $name = $request->name;
        $device_id = $request->device_id;
        $phone = $request->phone;
        $email = $request->email;
        $from_url = $request->from_url;
        $message = $request->message;
        $password = $request->password;
        $user = User::where('email', $email)->count();

        $parts = explode('@', $email);
        $password = $parts[0];
        $password;

        if ($user > 0) {
            $credentials = $request->only('email', 'password');

            // updating the url
            $user = User::where('email', $email)->first();
            $conversation = Conversation::where('user_id', $user->id)->first();
            $conversation->from_url = $from_url ?? '';
            $conversation->device_id = $device_id;
            $conversation->save();
            // updating the url

            Auth::attempt($credentials);

            $user->device_id = $conversation->device_id;
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
            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials, $request->boolean('remember'));

            $auth = Auth::user();
            $conversation = new Conversation();
            $conversation->user_id = $auth->id;
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

            $user->device_id = $conversation->device_id;
            return response()->json($user);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Auth::guard('admin')->attempt($credentials, $request->boolean('remember'));
        $data = Auth::guard('admin')->user();
        return response()->json($data);
    }
}
