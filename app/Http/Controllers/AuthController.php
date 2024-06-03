<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        //
    }
    public function postLogin(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;
            return CustomResponse::response(CustomResponse::$success_array,'Login successfully.',false,[
                'token'=>$token
            ]);
        }

        return CustomResponse::response(CustomResponse::$error_array,'The provided credentials do not match our records. please go to register form if do not have an account yet.',false);
    }
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
    
        $user = $this->create($request->all());
    
        Auth::login($user);
    
        return CustomResponse::response(CustomResponse::$success_array,'Registrations successfully.',false);
    }    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function logout(){
        Auth::logout();
        return CustomResponse::response(CustomResponse::$success_array,'Logout successfully.',false);
    }
}
