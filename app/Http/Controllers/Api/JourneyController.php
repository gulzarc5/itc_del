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


    public function JourneyStart(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'user_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
    }
}
