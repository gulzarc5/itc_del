<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function endedReport()
    {
        return view('admin.report.ended_delivery');
    }

    public function endedReportAjax()
    {
        $report = DB::table('start_journey')
            ->select('start_journey.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->leftjoin('beat_name','beat_name.id','=','start_journey.beat_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('start_journey.status',2);
            return datatables()->of($report->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="#" class="btn btn-info btn-sm" target="_blank">View Details</a>';
                return $btn;
            }) 
            ->addColumn('day', function($row){
                $date = Carbon::parse($row->created_at);
                return $date->format('l');
            })
            ->rawColumns(['action','day'])
            ->make(true);
    }
}
