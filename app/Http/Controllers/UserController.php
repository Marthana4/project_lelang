<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', 500]);
        }
        $user = JWTAuth::user();

        return response()->json([
            'success'=>true,
            'message'=>'Login berhasil',
            'token'=>$token,
            'user'=>$user,
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required|string',
                'alamat' => 'required',
                'no_hp' => 'required',
                'username' => 'required',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $user = new User();
        $user->nama = $request ->nama;
        $user->alamat = $request ->alamat;
        $user->no_hp = $request ->no_hp;
        $user->username = $request ->username;
        $user->password = Hash::make($request ->password);
        $user->level = 'pengguna';
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function logincheck()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Token'
                ]);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired!'
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Token!'
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Absent'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ]);
    }

    public function logout(Request $request)
    {
        if (JWTAuth::invalidate(JWTAuth::getToken())) {
            return Response()->json(['message'=>'Anda sudah log out']);
        } else {
            return Response()->json(['message'=>'Anda gagal log out']);
        }
    }

    public function getall()
    {
        $user = User::all();
        return Response()->json($user);
    }

    public function show($id)
    {
        $user = User::find($id);
        return Response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required|string',
                'alamat' => 'required',
                'no_hp' => 'required',
                'username' => 'required',
                'password' => 'required|min:6',
                'level' => 'required',
            ]
        );

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $user = new User();
        $user->nama = $request ->nama;
        $user->alamat = $request ->alamat;
        $user->no_hp = $request ->no_hp;
        $user->username = $request ->username;
        $user->password = Hash::make($request ->password);
        $user->level = $request -> level;
        $user->save();

        $token = JWTAuth::fromUser($user);

        $data = User::where('id', '=', $user->id)->first();
        
        return response()->json([
            'success' => true,
            'message' => 'User data sucessfully added',
            'data' => $data
        ]);
    }

    public function edit(Request $req, $id)
    {
        $req->validate([
            'nama'=>'required',
            'alamat'=>'required',
            'no_hp'=>'required',
            'username'=>'required',
        ]);

        $update=User::find($id);

        $update->nama = $req->nama;
        $update->alamat = $req->alamat;
        $update->no_hp = $req->no_hp;
        $update->username = $req->username;

        $update->update();

        return response()->json([
            'success' => true,
            'message' => 'User data sucessfully updated',
            'data' => $update
        ]);
    }

    public function destroy($id)
    {
        $delete = User::where('id', '=', $id)->delete();

        if ($delete) {
            return response()->json(['message' => 'Berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Gagal dihapus']);
        }
    }
}
