<?php 

namespace App\Http\Controllers\Api;

use DB;
use App\Models\Activity;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\PasswordResetRequest;
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

    public function __construct(CustomerService $customerService, PetRepository $petRepository, ActivityRepository $activityRepository) 
    {
        $this->customerService = $customerService;
        $this->petRepository = $petRepository;
        $this->activityRepository = $activityRepository;
    }

    public function createPassword(PasswordRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->only(['email', 'password']);
            
            $this->customerService->createPassword($data);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Senha cadastrada com sucesso!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function resetPasswordLink(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $this->customerService->generatePasswordLink($request->email);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Recuperação de senha solicitada! Verifique seu e-mail.'
            ], 200);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        } 
    }

    public function resetPasswordForm(Request $request, $token) 
    {
        return view('password.formResetPassword', [
            'token' => $token
        ]);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->only(['password', 'token']);
            
            $this->customerService->updatePassword($data);

            DB::commit();
            
            return redirect()
                ->route('api_profile_password_reset_form', $data['token'])
                ->with('success', 'Nova senha cadastrada com sucesso! Volte ao aplicativo e efetue o login novamente.');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('api_profile_password_reset_form', $data['token'])
                ->with('error', $e->getMessage());
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