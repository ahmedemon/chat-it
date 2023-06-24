<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $pageTitle = "My Profile";
        $setting = Setting::where('admin_id', Auth::guard('admin')->user()->id)->first();
        return view('admin.profile.index', compact('pageTitle', 'setting'));
    }
    public function update(Request $request, $id)
    {
        $file = new FileManager();

        $admin = Admin::findOrFail($id);
        if ($request->profile_logo) {
            if ($admin->profile_logo != null) {
                Storage::disk('admin')->delete($admin->profile_logo);
            }
            $photo = $request->profile_logo;
            $file->folder('admin')->prefix('profile_logo')
                ->upload($photo) ?
                $admin->profile_logo = $file->getName() : null;
        }
        $admin->save();

        $setting = Setting::where('admin_id', $admin->id)->first();
        $setting->site_greetings = $request->site_greetings;
        $setting->site_link = $request->site_link;
        $setting->link_title = $request->link_title;
        $setting->btn_color = $request->btn_color;
        $setting->login_header = $request->login_header;

        if ($request->site_logo) {
            if ($setting->site_logo != null) {
                Storage::disk('admin')->delete($setting->site_logo);
            }
            $photo = $request->site_logo;
            $file->folder('admin')->prefix('site_logo')
                ->upload($photo) ?
                $setting->site_logo = $file->getName() : null;
        }

        $setting->save();
        return redirect()->back();
    }
}
