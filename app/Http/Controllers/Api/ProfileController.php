<?php 

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Repositories\PetRepository;
use App\Repositories\ActivityRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private CustomerService $customerService;
    private PetRepository $petRepository;
    private ActivityRepository $activityRepository;

    public function __construct(CustomerService $customerService, PetRepository $petRepository, ActivityRepository $activityRepository) {
        $this->customerService = $customerService;
        $this->petRepository = $petRepository;
        $this->activityRepository = $activityRepository;
    }

    public function createPassword(PasswordRequest $request)
    {
        try {
            $data = $request->only(['email', 'password']);
            
            $this->customerService->createPassword($data);

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
        
        $myPets = $this->petRepository->getMyPets($customer->id);
                
        return response()->json(['pets' => $myPets]);
    }

    public function petsActivities(Request $request, $id)
    {   
        $startDate = $request->startDate ?: date('Y-m-01');
        $endDate = $request->endDate ?: date('Y-m-t');
        
        $activities = $this->activityRepository->getActivitiesByAndDate($id, $startDate, $endDate);
        
        return response()->json(['activities' => $activities]);
    }

    public function petActivity(Request $request, $id)
    {
        $activity = $this->activityRepository->getById($id);
        
        return response()->json(['activity' => $activity]);
    }
}