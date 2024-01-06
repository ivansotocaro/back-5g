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
//     /**
//     * Store a newly created resource in storage.
//     * @return RedirectResponse
//     */
    public function store(Request $request): JsonResponse
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->document = $request['document'];
        $user->password = bcrypt($request['password']);
        $user->save();
//        lo que me trae el token
        $token = JWTAuth::getToken();
        $apy = JWTAuth::getPayload($token)->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
        ]);

    }

}
