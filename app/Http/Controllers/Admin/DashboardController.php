<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentWithdraw;
use App\Models\LosingBonus;
use App\Models\SuperAgentWithdraw;
use App\Models\Transaction;
use App\Models\UsersPackage;
use App\Models\WinningBonus;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Dashboard';
        return view('admin.index', compact('pageTitle'));
    }
}
