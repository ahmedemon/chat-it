<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentWithdraw;
use App\Models\Conversation;
use App\Models\LosingBonus;
use App\Models\Message;
use App\Models\SuperAgentWithdraw;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UsersPackage;
use App\Models\WinningBonus;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard';
        return view('admin.index', compact('pageTitle'));
    }

    public function viewConversation($id)
    {
        $conversation = Conversation::with('messages')->findOrFail($id);
        $data = $conversation->messages;
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
}
