<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyLoginRequest;
use App\Http\Requests\CompanyRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->guard('api')->user();
        return response()->json([
            'company' => $user
        ]);
    }

    public function register(CompanyRegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'token' => $user->createToken($device)->accessToken,
            'company' => $user
        ]);
    }

    public function login(CompanyLoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $device = substr($request->userAgent() ?? '', 0, 255);
                $token = $user->createToken($device)->accessToken;
                $response = ['token' => $token, 'company' => $user];
                return response()->json($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response()->json($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response()->json($response, 422);
        }
    }
}
