<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function userLogin(Request $request)
    {
        $user_mobile = $request->input('mobile');
        $user_pass = $request->input('password');
        
        if (!empty($user_mobile) && !empty($user_pass)) {
            $user = User::where('mobile',$user_mobile)->first();
            if ($user) {
                if(Hash::check($user_pass, $user->password)){ 
                    $user_update = User::where('id',$user->id)
                        ->update([
                        'api_token' => Str::random(60),
                    ]);
    
                    $user = User::where('id',$user->id)->first();
                    $response = [
                        'status' => true,
                        'message' => 'User Logged In Successfully',    
                        'data' => $user,
                    ];    	
                    return response()->json($response, 200);
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'Mobile No or password Wrong',   
                        'data' => null,
                    ];    	
                    return response()->json($response, 200);
                }
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Mobile No or password Wrong',  
                    'data' => null,  
                ];    	
                return response()->json($response, 200);
            }
        }else{
            $response = [
                'status' => false,
                'message' => 'Required Field Can Not be Empty',  
                'data' => null,  
            ];    	
            return response()->json($response, 200);
        }       
    }
}
