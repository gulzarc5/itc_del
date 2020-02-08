<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletReport;
use App\Exports\DeliveryReport;
use Illuminate\Contracts\Encryption\DecryptException;

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
            ->where('start_journey.status',2)
            ->orderBy('start_journey.id','desc');
            return datatables()->of($report->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.route('admin.delivery_details',['journey_id'=>encrypt($row->id)]).'" class="btn btn-info btn-sm" target="_blank">View Details</a>';
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
        // return redirect()->back();
        
        return Excel::download(new DeliveryReport($s_date,$e_date,$del_boy_id,$beat_id), ''.date('Y-m-d').'-report.xlsx');
    }

    public function outletDeliveryReport()
    {
        $delivery_boy = DB::table('user')->get();
        $beat = DB::table('beat_name')->get();
        return view('admin.report.outlet_delivery',compact('delivery_boy','beat'));
    }

    public function outletDeliveryReportAjax()
    {
        $report = DB::table('delivery_details')
            ->select('delivery_details.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name','outlet.name as outlet_name','outlet.address as outlet_address')
            ->leftjoin('user','user.id','=','delivery_details.del_boy_id')
            ->leftjoin('outlet','outlet.id','=','delivery_details.out_let_id')
            ->leftjoin('beat_name','beat_name.id','=','outlet.beat_id')
            ->leftjoin('start_journey','start_journey.id','=','delivery_details.journey_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->orderBy('delivery_details.id','desc'); 
                   
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

    public function outletDeliveryReportSearch(Request $request)
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
        
        $report = DB::table('delivery_details')
            ->select('delivery_details.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name','outlet.name as outlet_name','outlet.address as outlet_address')
            ->leftjoin('user','user.id','=','delivery_details.del_boy_id')
            ->leftjoin('outlet','outlet.id','=','delivery_details.out_let_id')
            ->leftjoin('beat_name','beat_name.id','=','outlet.beat_id')
            ->join('start_journey','start_journey.id','=','delivery_details.journey_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->whereBetween('delivery_details.created_at',[$start, $end]);
        if (isset($user_id) && !empty($user_id)) {
            $report = $report->where('delivery_details.del_boy_id',$user_id);
        }
        if (isset($beat_id) && !empty($beat_id)) {
            $report = $report->where('start_journey.beat_id',$beat_id);
        }

        $report = $report->orderBy('start_journey.id','desc')->get();      
        
        $delivery_boy = DB::table('user')->get();
        $beat = DB::table('beat_name')->get();
        return view('admin.report.outlet_delivery',compact('delivery_boy','beat','report'));
    }

    public function outletDeliveryReportExport($s_date,$e_date,$del_boy_id=null,$beat_id=null)
    {
        $data = [
            's_date' => $s_date,
            'e_date' => $e_date,
            'del_boy_id' => $del_boy_id,
            'beat_id' => $beat_id,
        ];
        return Excel::download(new OutletReport($s_date,$e_date,$del_boy_id,$beat_id), ''.date('Y-m-d').'-outlet-report.xlsx');
    }

    public function deliveryDetails($journey_id)
    {
        try {
            $journey_id = decrypt($journey_id);
        }catch(DecryptException $e) {
            return redirect()->back();
        }

        $journey = DB::table('start_journey')
            ->select('user.name as boy_name','start_journey.created_at as date')
            ->leftjoin('user','user.id','=','start_journey.user_id')
            ->where('start_journey.id',$journey_id)
            ->first();

        $report = DB::table('delivery_details')
            ->select('delivery_details.*','user.name as boy_name','vehicle_type.name as vehicle_name','beat_name.name as beat_name','outlet.name as outlet_name','outlet.address as outlet_address')
            ->leftjoin('user','user.id','=','delivery_details.del_boy_id')
            ->leftjoin('outlet','outlet.id','=','delivery_details.out_let_id')
            ->leftjoin('beat_name','beat_name.id','=','outlet.beat_id')
            ->leftjoin('start_journey','start_journey.id','=','delivery_details.journey_id')
            ->leftjoin('vehicle_type','vehicle_type.id','=','start_journey.vehicle_id')
            ->where('delivery_details.journey_id',$journey_id)
            ->orderBy('delivery_details.id','desc')->get();
        return view('admin.report.outlet_delivery_details',compact('report','journey'));

    }
}
