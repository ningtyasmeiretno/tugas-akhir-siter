<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pimpinan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pimpinan = Pimpinan::where('email', $request->email)->first();

        if (!$pimpinan || !Hash::check($request->password, $pimpinan->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $pimpinan,
            'token'   => $pimpinan->createToken('authToken')->accessToken
        ]);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',
            ]);
        }
    }
}