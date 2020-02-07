<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveryReport;

class ReportController extends Controller
{
    public function endedReport()
    {
        $delivery_boy = DB::table('user')->get();
        $beat = DB::table('beat_name')->get();
        return view('admin.report.ended_delivery',compact('delivery_boy','beat'));
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

    public function endedReportSearch(Request $request)
    {
        $request->validate([
            's_date' => 'required',
            'e_date' => 'required',
        ]);

        $s_date = $request->input('s_date');
        $e_date = $request->input('e_date');
        $user_id =  $request->input('user_id');
        $beat_id =  $request->input('beat_id');
        
        $start = Carbon::parse($s_date)->startOfDay();
        $end = Carbon::parse($e_date)->endOfDay();

        $report = DB::table('start_journey')
            ->select('start_journey.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->leftjoin('beat_name','beat_name.id','=','start_journey.beat_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('start_journey.status',2)
            ->whereBetween('start_journey.created_at',[$start, $end]);
        if (isset($user_id) && !empty($user_id)) {
            $report = $report->where('start_journey.user_id',$user_id);
        }
        if (isset($beat_id) && !empty($beat_id)) {
            $report = $report->where('start_journey.beat_id',$beat_id);
        }

        $report = $report->orderBy('start_journey.id','desc')->get();

        $delivery_boy = DB::table('user')->get();
        $beat = DB::table('beat_name')->get();
        return view('admin.report.ended_delivery',compact('delivery_boy','beat','report'));
    }

    public function endedReportExport($s_date,$e_date,$del_boy_id=null,$beat_id=null)
    {
        $data = [
            's_date' => $s_date,
            'e_date' => $e_date,
            'del_boy_id' => $del_boy_id,
            'beat_id' => $beat_id,
        ];
        return redirect()->back();
        //return Excel::download(new DeliveryReport($s_date,$e_date,$del_boy_id,$beat_id), ''.date('Y-m-d').'-report.xlsx');
    }
}
