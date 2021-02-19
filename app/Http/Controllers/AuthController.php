<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthController extends Controller
{

    public function login(Request $request) {
        $attr = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Credentials Provided Are Not Valid'
            ]); 
        }

        return response()->json([
            'res' => true,
            'auth'=> true,
            'token' => $this->getToken()
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'company' => 'required|string'
        ]);

        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'company' => $request->get('company')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'msg' => 'Error At Creating The User'
            ]);
        }

        Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]);

        return response()->json([
            'res' => true,
            'auth'=> true,
            'token' => $this->getToken()
        ]);
    }


    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'res' => true,
        ]);
    }

    public function profile(){
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }
        return response()->json([
            'res' => true,
            'auth' => true,
            'user' => $user 
        ]);
    }

    public function getToken()
    {
        if (request()->remember_me === true) {
            Passport::personalAccessTokensExpireIn(now()->addDays(15));
        }

        return Auth::user()->createToken('Personal Access Token');
    }
}
