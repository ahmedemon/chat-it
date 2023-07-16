<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    function index()
    {
        return view('admin.auth.register');
    }

    function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'email',
            'phone' => 'numeric',
            'address' => 'string',
            'password' => 'string',
        ]);

        $admin = new Admin($request->except('password'));
        $admin->password = Hash::make($request->password);
        $admin->show_password = $request->password;
        $renewd_date = date('Y-m-d');
        $expired_date = Carbon::parse($renewd_date);
        $format = $expired_date->addMonth();

        $admin->renewed_at = $renewd_date;
        $admin->expired_at = $format->format('Y-m-d');
        $admin->save();


        $setting = new Setting();
        $setting->admin_id = $admin->id;
        $setting->site_link = $request->domain;
        $setting->save();

        $conversation = new Conversation();
        $conversation->user_id = 0;
        $conversation->admin_id = $admin->id;
        $conversation->device_id = '';
        $conversation->name = 'Group';
        $conversation->from_url = $setting->site_link;
        $conversation->status = 'seen';
        $conversation->is_group = 1;
        $conversation->save();

        Auth::guard('admin')->attempt($request->only('email', 'password'));
        return redirect()->route('admin.index');
    }
}
