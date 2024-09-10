<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function login(Request $request){
        $validator =Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }
        $credential = $request->only('email','password');
        $token = auth()->guard('api')->attempt($credential);
        $user = auth()->guard('api')->user();
        tap(User::where(['email'=>$request->email]))->update(['login_token'=>$token])->first();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password anda salah'
            ],404);
        }else{
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token
            ],200);
        }
    }
    public function logout(Request $request,$id){
        User::where('id',$id)->update(['login_token' => null]);
        auth()->guard('api')->logout();
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil, token telah dihapus'
        ],200);
    }
    public function add(Request $request){
        $user = User::where(['login_token'=>$request->token])->first();
        if ($request->token == null) {
            return response()->json(['message' => 'Unauthorization user'],401);
        }else{
            $validator =Validator::make($request->all(),[
                'name' => 'required',
                'telp' => 'required',
                'email' => 'required',
                'password' => 'required',
                'alamat' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(),422);
            }
            $user = User::create([
                'name' => $request->name,
                'telp' => $request->telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'password' => bcrypt($request->password),
            ]);
            if ($request->hasFile('foto')) {
                $filename = $request->file('foto')->storeAs('foto_user', $request->name.'.'.$request->file('foto')->getClientOriginalExtension());
                $user->foto = $filename;
                $user->save();
            }
            if ($user) {
                return response()->json([
                    'success' => true,
                    'user' => $user
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'gagal tambah data'
                ],409);
            }
        }
    }
    public function show(Request $request){
        $user = User::where(['login_token'=>$request->token])->first();
        if ($request->token == null) {
            return response()->json(['message' => 'Unauthorization user'],401);
        }else{
            $listUser = User::all();
            foreach ($listUser as $listUsers) {
                if ($listUsers->foto) {
                    $listUsers->foto = asset('storage/'.$listUsers->foto);
                }
            }
            return response()->json(['users' => $listUser],200);
        }
    }
    public function showId(Request $request,$id){
        $user = User::where(['login_token'=>$request->token])->first();
        if ($request->token == null) {
            return response()->json(['message' => 'Unauthorization user'],401);
        }else{
            $user = User::where('id',$id)->first();
            if ($user) {
                $data_user = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'telp' => $user->telp,
                    'alamat' => $user->alamat,
                    'foto' => $user->foto ? asset('storage/'.$user->foto) : null,
                ];
            }
            return response()->json(['user' => $data_user],200);
        }
    }
    public function edit(Request $request,$id){
        $user = User::where(['login_token'=>$request->token])->first();
        if ($request->token == null) {
            return response()->json(['message' => 'Unauthorization user'],401);
        }else{
            $validator =Validator::make($request->all(),[
                'name' => 'required',
                'telp' => 'required',
                'email' => 'required',
                'password' => 'required',
                'alamat' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(),422);
            }
            $user = User::where('id',$id)->update([
                'name' => $request->name,
                'telp' => $request->telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'password' => bcrypt($request->password),
            ]);
            if ($request->hasFile('foto')) {
                $filename = $request->file('foto')->storeAs('foto_user', $request->name.'.'.$request->file('foto')->getClientOriginalExtension());
                $user->foto = $filename;
                $user->save();
            }
            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => ' berhasil edit data'
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'gagal edit data data'
                ],409);
            }
        }
    }
    public function delete(Request $request,$id){
        $user = User::where(['login_token'=>$request->token])->first();
        if ($request->token == null) {
            return response()->json(['message' => 'Unauthorization user'],401);
        }else{
            $user = User::where('id',$id)->delete();
            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil di hapus'
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'gagal hapus data'
                ],409);
            }
        }
    }
}
