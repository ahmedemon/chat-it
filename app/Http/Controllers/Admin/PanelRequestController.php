<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelRequestController extends Controller
{
    public function index()
    {
        $pageTitle = "Dashboard";
        return view('admin.panel.index', compact('pageTitle'));
    }
    public function admins()
    {
        $admins = Admin::paginate(20);
        $pageTitle = "Admin List";
        return view('admin.panel.admins', compact('pageTitle', 'admins'));
    }

    public function group()
    {
        $pageTitle = "Group User List";
        $auth = Auth::guard('admin')->user();
        $conversations = Conversation::with('user')->where('admin_id', $auth->id)->where('is_group', 0)->paginate(20);
        return view('admin.panel.group-users', compact('pageTitle', 'conversations'));
    }

    public function approve($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status = 1;
        $admin->save();
        return redirect()->back();
    }
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return redirect()->back();
    }

    public function makeMonitor($id)
    {
        $admin = User::findOrFail($id);
        $admin->is_monitor = 1;
        $admin->save();
        return redirect()->back();
    }

    public function removeMonitor($id)
    {
        $admin = User::findOrFail($id);
        $admin->is_monitor = 0;
        $admin->save();
        return redirect()->back();
    }
}
