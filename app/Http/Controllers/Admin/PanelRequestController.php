<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class PanelRequestController extends Controller
{
    public function index()
    {
        $admins = Admin::where('status', 0)->paginate(20);
        $pageTitle = "Panel Request List";
        return view('admin.panel.index', compact('pageTitle', 'admins'));
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
}
