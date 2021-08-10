<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Customer;
use App\Http\Requests\PetRequest;
use App\Repositories\PetRepository;
use App\Services\PetService;
use DB;
use Illuminate\Http\Request;

class PetController extends Controller
{
    const MEDIA_COLLECTION = 'pets';

    private PetService $service;
    
    private PetRepository $repository;


    public function __construct(PetService $service, PetRepository $repository)
    {
        $this->middleware('auth');

        $this->service = $service;

        $this->repository = $repository;
	}

    public function index(Request $request)
    {
    	$pets = $this->repository->getAll($request->term);
   
    	return view('pet.index', compact('pets'));
    }

    public function create()
    {
        $customers = Customer::all();

        return view('pet.create', compact('customers'));
    }

    public function store(PetRequest $request)
    {
        DB::beginTransaction();

        try {

            $this->service->store($request);
            
            DB::commit();

            return redirect()
                ->route('pet_index')
                ->with('success', 'Pet cadastrado com sucesso!');

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('pet_index')
                ->with('error', 'Ocorreu um erro ao salvar o pet!');
        }
    }

    public function show($id)
    {
        $pet = $this->repository->getById($id);

    	return view('pet.show', [
            'pet' => $pet,
            'photos' => $pet->getMedia('pets')
        ]);
    }

    public function edit($id)
    {
        $pet = $this->repository->getById($id);

        $customers = Customer::all();

        return view('pet.edit', [
            'pet'       => $pet,
            'customers' => $customers
        ]);
    }

    public function update(PetRequest $request, $id)
    {
        DB::beginTransaction();

        try {

            $this->service->update($request, $id);
            
            DB::commit();

            return redirect()
                ->route('pet_index')
                ->with('success', 'Pet editado com sucesso!');

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('pet_index')
                ->with('error', 'Ocorreu um problema ao editar o pet!');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
            
        try {
                                
            $this->service->delete($id);
            
            DB::commit();

            return redirect()
                ->route('pet_index')
                ->with('success', 'Pet removido com sucesso!'); 
            
        } catch(\Exception $e) {
            
            DB::rollback();

            return redirect()
                ->route('pet_index')
                ->with('error', 'Ocorreu um ao tentar excluir o pet!'); 
        }
    }
}
