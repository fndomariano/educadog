<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Customer;
use App\Http\Requests\PetRequest;
use DB;
use Illuminate\Http\Request;

class PetController extends Controller
{
    const MEDIA_COLLECTION = 'pets';

    public function __construct()
    {
        $this->middleware('auth');
	}

    public function index(Request $request)
    {
        $term = $request->term;

    	$pets = Pet::query()
    		->where('name', 'LIKE', '%'.$term.'%')
    		->paginate(10);
   
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

            $pet = new Pet;
            $pet->name  = $request->name;
            $pet->breed = $request->breed;
            $pet->customer_id = (int) $request->customer_id;
            $pet->photo = null;
            $pet->addMedia($request->file('photo'))->toMediaCollection(self::MEDIA_COLLECTION);
            $pet->save();
            
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
        $pet = Pet::find($id);

    	if (!$pet) {
			abort(404);
		}

    	return view('pet.show', [
            'pet' => $pet,
            'photos' => $pet->getMedia('pets')
        ]);
    }

    public function edit($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            abort(404);
        }

        $customers = Customer::all();

        return view('pet.edit', [
            'pet'       => $pet,
            'customers' => $customers
        ]);
    }

    public function update(PetRequest $request, $id)
    {
        try {

            $pet = Pet::find($id);
            $pet->name  = $request->name;
            $pet->breed = $request->breed;
            $pet->photo = $request->photo;
            $pet->customer_id = (int) $request->customer_id;
            
            $media = $pet->getMedia(self::MEDIA_COLLECTION);

            if ($request->file('photo') && isset($media[0])) {
                foreach ($media as $photo) {
                    $photo->delete();    
                }
            }

            if ($request->file('photo')) {
                $pet->addMedia($request->file('photo'))->toMediaCollection(self::MEDIA_COLLECTION);
            }

            $pet->save();
            
            DB::commit();

            return redirect()
                ->route('pet_index')
                ->with('success', 'Pet editado com sucesso!');

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('pet_index')
                ->with('error', 'Ocorreu um erro ao editar o pet!');
        }
    }

    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            abort(404);
        }

        DB::beginTransaction();
            
        try {
                                
            $pet->delete();
            
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
