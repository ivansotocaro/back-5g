<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function getUser(): JsonResponse
    {
        $user = User::all();
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
//        lo que me trae el token
//        $token = JWTAuth::getToken();
//        $apy = JWTAuth::getPayload($token)->toArray();
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->document = $request['document'];
        $user->password = bcrypt($request['password']);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
        ]);

    }

}
