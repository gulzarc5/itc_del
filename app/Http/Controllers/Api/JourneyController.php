<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Validator;

class JourneyController extends Controller
{
    public function journeyFormLoad()
    {
        $vehicle_type = DB::table('vehicle_type')->where('status',1)->get();
        $beat_name = DB::table('beat_name')->where('status',1)->get();
        $data = [
            'vehicle_type' => $vehicle_type,
            'beat_name' => $beat_name,
        ];
        $response = [
            'status' => true,
            'message' => 'User Logged In Successfully',    
            'data' => $data,
        ];    	
        return response()->json($response, 200);
    }


    public function journeyStart(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'beat_id' => 'required',
            'vehicle_id' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'start_latitude' => 'required',
            'start_longtitude' => 'required',
            'start_address' => 'required',
        ]);
    
        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => 'Input Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
                'data' => null,
            ];
            return response()->json($response, 200);
        }

        $journey = DB::table('start_journey')
            ->insertGetId([
                'user_id' => $request->input('user_id'),
                'beat_id' => $request->input('beat_id'),                
                'vehicle_id' => $request->input('vehicle_id'),
                'start_date' => $request->input('start_date'),
                'start_time' => $request->input('start_time'),
                'start_latitude' => $request->input('start_latitude'),
                'start_longtitude' => $request->input('start_longtitude'),
                'start_address' => $request->input('start_address'),
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        if ($journey) {
            $data = [
                'journey_id' => $journey,
                'beat_id' => $request->input('beat_id'),
            ];
            $response = [
                'status' => true,
                'message' => 'Journey Started Successfully',
                'error_code' => false,
                'error_message' => null,
                'data' => $data,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false, 
                'error_message' => null,
                'data' => null,
            ];
            return response()->json($response, 200);
        }
    }

    public function outLetList($beat_id)
    {
        $outlet_list = DB::table('outlet')->where('beat_id',$beat_id)->where('status',1)->get();

        if($outlet_list->count() > 0){
            $response = [
                'status' => true,
                'message' => 'Outlet List',    
                'data' => $outlet_list,
            ];    	
            return response()->json($response, 200);
        }else {
            $response = [
                'status' => false,
                'message' => 'No Outlet Found',    
                'data' => [],
            ];    	
            return response()->json($response, 200);
        }
    }

    public function journeyEnd(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'journey_id' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
            'end_latitude' => 'required',
            'end_longtitude' => 'required',
            'end_address' => 'required',            
            'total_km' => 'required',
        ]);
    
        if ($validator->fails()) {

            $response = [
                'status' => false,
                'message' => 'Input Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $journey = DB::table('start_journey')
            ->where('id',$request->input('journey_id'))
            ->update([
                'user_id' => $request->input('user_id'),
                'end_date' => $request->input('end_date'),
                'end_time' => $request->input('end_time'),
                'end_latitude' => $request->input('end_latitude'),
                'end_longtitude' => $request->input('end_longtitude'),
                'end_address' => $request->input('end_address'),
                'total_km' => $request->input('total_km'),
                'status' => 2,
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        
        if ($journey) {
            $response = [
                'status' => true,
                'message' => 'Journey Ended Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false, 
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }
}
