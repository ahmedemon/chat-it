<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard';
        return view('admin.index', compact('pageTitle'));
    }

    public function viewConversation($id)
    {
        $conversation = Conversation::with(['messages', 'user'])->findOrFail($id);
        $data = [$conversation->messages, $conversation->user];
        return response()->json($data);
    }

    public function sendMessage(Request $request, $id)
    {
        $text = $request->message_body;
        $conversation = Conversation::findOrFail($id);
        $user = User::findOrFail($conversation->user_id);
        $data = Message::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id,
            'type' => 'receive',
            'content' => $text,
        ]);
        return response()->json($data);
    }

    public function getInboxes()
    {
        $data = Conversation::with('messages')->orderBy('updated_at', 'desc')->get();
        return response()->json($data);
    }

    public function seen($id)
    {
        $data = Message::where('conversation_id', $id)->update(['status' => 'seen']);

        $data1 = Conversation::findOrFail($id);
        $data1->status = 'seen';
        $data1->save();

        return response()->json([$data, $data1]);
    }

    public function sendEmail($id)
    {
        $conversation = Conversation::findOrFail($id);
        $user = User::where('id', $conversation->user_id)->first();
        Mail::to($user->email)->send(new SendEmail($user, $conversation));
        return response()->json(['success' => true]);
    }
}
