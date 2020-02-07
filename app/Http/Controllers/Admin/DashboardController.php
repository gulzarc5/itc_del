<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        $started_journey = DB::table('start_journey')
            ->select('journey.*','user.name as boy_name','beat.name as beat_name')
            ->leftjoin('user','user.id','=','journey.')
            ->where('stat');
        return view('admin.dashboard');
    }
}
