<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $data = Conversation::with('messages')->get();
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
        return response()->json($data);
    }
}
