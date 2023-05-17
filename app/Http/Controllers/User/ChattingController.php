<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChattingController extends Controller
{
    public function sendMessage()
    {
        $text = request()->message_body;
        $auth = Auth::user();
        $conversation = Conversation::findOrFail(request()->conversation_id);
        $data = new Message();
        $data = Message::create([
            'user_id' => $auth->id,
            'conversation_id' => $conversation->id,
            'type' => 'send',
            'content' => $text,
        ]);
        return response()->json($data);
    }
}
