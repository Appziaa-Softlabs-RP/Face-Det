<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaceRegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaceController extends Controller
{
    public function register(FaceRegisterRequest $request) : JsonResponse
    {
        // dd($request);
        return response()->json([
            'message' => 'Registration successful'
        ]);
    } 
}
