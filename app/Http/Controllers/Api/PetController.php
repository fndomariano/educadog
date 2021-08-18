<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PetRepository;
use Illuminate\Http\Request;

class PetController extends Controller
{
    private PetRepository $repository;

    public function __construct(PetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function myPets()
    {
        $customer = auth('api')->user();
        
        $pets = $this->repository->getAllByCustomer($customer->id);
        
        return response()->json($pets);
    }
}