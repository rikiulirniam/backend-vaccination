<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function index() {
        $user = Auth::user();

        return response()->json($user);
    }
    public function register(Request $request){
        return response()->json([
            'hello' => 'world',
            'data' => $request->all()
        ]);
    }
    public function login(Request $request) {
        $society = Society::query()
            ->where('id_card_number', $request->get('id_card_number'))
            ->where('password', $request->get('password'))
            ->first();


        if ($society == null) {
            return response()->json([
                'message' => 'ID Card Number or Password incorrect'
            ], 401);
        }

        Auth::loginUsingId($society->id);
        $user = Auth::user();
        $user['token'] = $user->createToken();

        return response()->json($user);
    }
    public function logout() {
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'message' => 'Invalid token'
            ], 401);
        }

        Auth::guard('api')->user()->deleteToken();
        return response()->json([
            'message' => 'Logout success'
        ]);
    }
}
