<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registrationForm()
    {
        return view('admin.user.user_registration');
    }

    public function registration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'gender' => 'required',
        ]);
        $password = str_random(8);

        DB::table('user')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'password' => Hash::make($password),
            'web_token' => $password,
            'created_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
            'updated_at' => Carbon::now()->setTimezone('Asia/Kolkata')->toDateTimeString(),
        ]);
        return redirect()->back()->with('message','Delivery Boy Registered Successfully');
    }

    public function userList()
    {
        return view('admin.user.user_list');
    }

    public function userListAjax()
    {
        $query = DB::table('user')->orderBy('id','desc');
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
