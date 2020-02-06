<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class VehicleController extends Controller
{
    public function vehicleList()
    {
        return view('admin.vehicle.vehicle_list');
    }

    public function vehicleListAjax()
    {
        $query = DB::table('vehicle_type')->orderBy('id','desc');
        return datatables()->of($query->get())
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $btn = '
            <a href="#" class="btn btn-info btn-sm" target="_blank">Edit</a>';
            if ($row->status == 1) {  
                $btn .= '<a href="#" class="btn btn-danger btn-sm">DeActivate</a>';
            }else{
                $btn .= '<a href="#" class="btn btn-primary btn-sm">Activate</a>';
            }
            return $btn;
        })
        ->addColumn('status_tab', function($row){
            if ($row->status == 1) {
                $btn = '<a href="#" class="btn btn-success btn-sm">Enabled</a>';
            }else{
                $btn = '<a href="#" class="btn btn-danger btn-sm">Disabled</a>';
            }
            return $btn;
        })
        ->rawColumns(['action','status_tab'])
        ->make(true);
    }
}
