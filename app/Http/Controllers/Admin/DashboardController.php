<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $messages = Message::with(['user', 'conversation'])->where('conversation_id', $conversation->id)->get();
        $data = [$messages, $conversation->user];
        return response()->json($data);
    }

    public function viewGroup($id)
    {
        $pageTitle = 'Dashboard';
        $conversation = Conversation::findOrFail($id);
        return view('admin.group', compact('pageTitle', 'conversation'));
    }

    public function sendMessage(Request $request, $id)
    {
        $text = $request->message_body;
        $conversation = Conversation::findOrFail($id);
        if ($conversation->user_id != 0) {
            $user = User::findOrFail($conversation->user_id);
        }

        $data = new Message();
        $data->user_id = $user->id ?? 0;
        $data->conversation_id = $conversation->id;
        $data->type = 'receive';
        $data->content = $text;

        if ($text == null) {
            $this->validate(request(), [
                'image' => 'required'
            ], [
                'image.required' => 'Please select an image first.'
            ]);
            $file = new FileManager();
            if (request()->image) {
                $photo = request()->image;
                $file->folder('messages')->prefix(date('d'))
                    ->upload($photo) ?
                    $data->content = $file->getName() : null;
                $data->check_image = 1; // check if sent an image
            }
        } else {
            $data->content = $text;
        }

        $data->save();
        return response()->json($data);
    }

    public function getInboxes()
    {
        $id = Auth::guard('admin')->user()->id;
        $data = Conversation::where('admin_id', $id)->with('messages')->orderBy('updated_at', 'desc')->where('is_group', 0)->get();
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
        $subject = request()->email_subject;
        $message = request()->email_message;
        $conversation = Conversation::findOrFail($id);
        $user = User::where('id', $conversation->user_id)->first();
        Mail::to($user->email)->send(new SendEmail($user, $conversation, $message, $subject));
        return response()->json(['success' => true]);
    }


    public function sound()
    {
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::findOrFail($id);
        if ($admin->sound == 1) {
            $admin->sound = 0;
            $admin->save();
        } else {
            $admin->sound = 1;
            $admin->save();
        }
        return redirect()->back();
    }
}
