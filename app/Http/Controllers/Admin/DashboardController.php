<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function index()
    {
        $title   = 'dashboard';
        $totalPatient       = User::where('role', 2)->count();
        $todayPatient       = User::where('role', 2)->whereDate('created_at', Carbon::today())->count();
        $totalDoctor        = User::where('role', 3)->count();
        $todayDoctor        = User::where('role', 3)->whereDate('created_at', Carbon::today())->count();
        $data     = compact('title', 'totalPatient', 'totalDoctor','todayPatient','todayDoctor');

        return view('admin.dashboard', $data);
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('admin')->with('success', 'logout success.');
    }
}
