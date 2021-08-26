<?php 

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Repositories\PetRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private PetRepository $repository;
    private CustomerService $service;

    public function __construct(CustomerService $service, PetRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function createPassword(PasswordRequest $request)
    {
        try {
            $data = $request->only(['email', 'password']);
            
            $this->service->createPassword($data);

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

    public function pets()
    {
        $customer = auth('api')->user();
        
        $myPets = $this->repository->getMyPets($customer->id);
                
        return response()->json(['pets' => $myPets]);
    }

    public function petsActivities($id)
    {
        $activities = Activity::query()
            ->where('pet_id', '=', $id)
            ->get();

        return response()->json(['activities' => $activities]);
    }
}