<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        $started_journey = DB::table('start_journey')
            ->select('start_journey.*','user.name as boy_name','beat_name.name as b_beat_name','vehicle_type.name as vehicle_name')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->leftjoin('beat_name','beat_name.id','=','start_journey.beat_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('start_journey.status',1)
            ->get();
        
        $end_journey = DB::table('start_journey')
            ->select('start_journey.*','user.name as boy_name','beat_name.name as b_beat_name','vehicle_type.name as vehicle_name')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->leftjoin('beat_name','beat_name.id','=','start_journey.beat_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('start_journey.status',2)
            ->whereDate('start_journey.created_at',Carbon::today())
            ->orderBy('start_journey.id','desc')
            ->get();

        $total_boy = DB::table('user')->where('status',1)->count();
        $total_beats = DB::table('beat_name')->where('status',1)->count();
        $total_outlet = DB::table('outlet')->where('status',1)->count();
        $vehicle_started = DB::table('start_journey')->where('status',1)->count();

        return view('admin.dashboard',compact('started_journey','end_journey','total_boy','total_beats','total_outlet','vehicle_started'));
    }
}
