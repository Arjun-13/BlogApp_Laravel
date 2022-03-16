<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $creds = $request->only(['email', 'password']);
        
        if(!$token= auth()->attempt($creds)) {
            
            return response()->json([
                'success' => false,
                'message' => 'invalid credentials'
            ]);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
    
    public function register(Request $request) {
        
        $encryptedPassword = Hash::make($request->password);
        
        $user = new User;
        
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $encryptedPassword;
            $user->save();
            return $this->login($request);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => ''.$ex
            ]);
        }
    }
    
    public function logout(Request $request) {
        
        try{
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout success'
            ]);
        } catch (Exception $ex) {
            return response()->json([
               'success' => false,
                'message' => ''.$ex
            ]);
        }
    }
    
}
