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

        $vehicle = DB::table('vehicle_type')->where('id',$request->input('vehicle_id'))->first();
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
                'per_km_cost' => $vehicle->km_cost,
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

    public function outLetList($beat_id,$user_id)
    {        
        $date = Carbon::now()->timezone('Asia/Kolkata');
        $outlet_list = DB::table('outlet')->where('beat_id',$beat_id)->where('status',1)->get();
        foreach ($outlet_list as $key => $value) {
            $count = DB::table('delivery_details')
                ->where('del_boy_id',$user_id)
                ->where('out_let_id',$value->id)
                ->whereDate('created_at',$date)
                ->count();
            if ($count > 0) {
                $value->del_status = 2;
            }else {
                $value->del_status = 1;# code...
            }
        }

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

        $journey = DB::table('start_journey')->where('id',$request->input('journey_id'))->first();
        $total_cost = 0;
        if ($journey) {
            $total_cost =  floatval($request->input('total_km')) * floatval($journey->per_km_cost);
        }else{
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
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
                'total_cost' => $total_cost,
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

    public function outletDelivery(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'journey_id' => 'required',
            'out_let_id' => 'required',
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

        $user_id = $request->input('user_id');
        $journey_id = $request->input('journey_id');
        $out_let_id = $request->input('out_let_id');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longtitude = $request->input('longtitude');

        $delivery = DB::table('delivery_details')
            ->insert([
                'journey_id' =>  $request->input('journey_id'),
                'del_boy_id' =>  $request->input('user_id'),
                'out_let_id' =>  $request->input('out_let_id'),
                'address' =>  $request->input('address'),
                'latitude' =>  $request->input('latitude'),
                'longtitude' =>  $request->input('longtitude'),                
                'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            ]);
        if ($delivery) {
            $outlet_check = DB::table('outlet')->where('id',$request->input('out_let_id'))->where('gps_update_status',1)->count();
            if ($outlet_check > 0) {
                if (!empty($address) && !empty($latitude) && !empty($longtitude)) {
                    DB::table('outlet')
                        ->where('id',$request->input('out_let_id'))
                        ->update([
                            'latitude' =>  $request->input('latitude'),
                            'longtitude' =>  $request->input('longtitude'),
                            'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
                            'gps_update_status' => 2,
                        ]);
                }
            }

            $response = [
                'status' => true,
                'message' => 'Delivered Successfully',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }else{
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
