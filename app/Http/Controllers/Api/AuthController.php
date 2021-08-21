<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function login(AuthRequest $request)
    {   
        try {
            
            $data = $request->only(['email', 'password']);

            $token = $this->service->authenticate($data);

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        
        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {   
        try {
            
            $this->service->unauthenticate($request->bearerToken());

            return response()->json([
                'success' => true,
                'message' => 'Logout efetuado com sucesso!'
            ], 200);
            
        }  catch (\Exception $e)  {
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    private function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ], 200);
    }
}
