<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function login(PasswordRequest $request)
    {   
        try {
            
            $token = $this->service->authenticate($request->email, $request->password);

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        return response()->json([
            'status' => 'bye!'
        ]);
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
