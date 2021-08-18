<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    private $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function create(PasswordRequest $request)
    {
        try {
            $this->service->createPassword($request->email, $request->password);

            return response()->json([
                'success' => true,
                'message' => 'Senha cadastrada com sucesso!'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
