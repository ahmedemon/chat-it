<?php

namespace App\Http\Controllers\User;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChattingController extends Controller
{
    public function sendMessage()
    {
        $text = request()->message_body;
        $auth = Auth::user();
        $conversation = Conversation::findOrFail(request()->conversation_id);

        $lastMessage = Message::where('conversation_id', $conversation->id)->latest()->first();
        $lastDataTimestamp = Carbon::parse($lastMessage->created_at);
        $currentTime = Carbon::now();
        $hoursDifference = $lastDataTimestamp->diffInHours($currentTime);

        $data = new Message();
        $data->user_id = $auth->id;
        $data->conversation_id = $conversation->id;
        $data->type = 'send';
        $data->site = $conversation->from_url ?? '';
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

        $conversation->status = 'receive';
        $conversation->updated_at = now();
        $conversation->save();

        if ($hoursDifference >= 4) {
            $data = new Message();
            $data->user_id = $auth->id;
            $data->conversation_id = $conversation->id;
            $data->type = 'receive';
            $data->content = 'Welcome Back Again, How can i assist you today?';
            $data->save();
            $greetings = 1;
        } else {
            $greetings = 0;
        }

        return response()->json([$data, $greetings]);
    }

    public function updateImage(Request $request, $id)
    {
        // return $request->all();
        $this->validate(
            $request,
            [
                'profile_picture' => 'required'
            ],
            [
                'profile_picture.required' => 'Please select an profile picture first.'
            ]
        );
        $user = User::findOrFail($id);
        $file = new FileManager();
        if ($request->profile_picture) {
            if ($user->profile_picture != null) {
                Storage::disk('user')->delete($user->profile_picture);
            }
            $photo = $request->profile_picture;
            $file->folder('profile')->prefix('user-profile')
                ->upload($photo) ?
                $user->profile_picture = $file->getName() : null;
        }
        $user->save();
        return response()->json($user);
    }
}
