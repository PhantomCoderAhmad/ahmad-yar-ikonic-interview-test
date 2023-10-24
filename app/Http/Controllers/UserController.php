<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::where('role_id',null)->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
    
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
    
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view("dashboard.users");
    }
    
    public function getAdminProfile(Request $request) { 
        $id = Auth::user()->id;
        $user_detail = User::find($id);
        return view("dashboard.user-profile")->with(['user_detail' => $user_detail]);
    }
    public function UpdateAdminProfile(Request $request) {
        $requested_data = $request->data;
        $data = array();
        parse_str($requested_data, $data);
        try{
            $user = User::find($data['id']);
            if(isset($data['password'])){
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
            else{
                $user->update([
                    'name' => $data['username'], 
                    'email' => $data['email'],
                ]);
            }
            
            return response()->json([
                'message' => "Profile has been updated successfully."
            ], 200);
        }catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'message' => "Sorry! Something went wrong."
            ], 422);
        }
    }
    public function edit($id){
        $user = User::find($id);
        return view("dashboard.update-user")->with(['user' => $user])->render();
    }
    public function update(Request $request){
        $requested_data = $request->data;
        $data = array();
        parse_str($requested_data, $data);
        try{
            $user = User::find($data['id']);
            if(isset($data['password'])){
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
            else{
                $user->update([
                    'name' => $data['username'], 
                    'email' => $data['email'],
                ]);
            }
            
            return response()->json([
                'message' => "Profile has been updated successfully."
            ], 200);
        }catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'message' => "Sorry! Something went wrong."
            ], 422);
        }
    }
    public function destroy($id){
        $user = User::find($id)->delete();
        if($user){
            return response()->json([
                'message' => "User has been deleted successfully."
            ], 200);
        }
    }
}
