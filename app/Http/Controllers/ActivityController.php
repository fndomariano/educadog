<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Pet;
use App\Http\Requests\ActivityRequest;
use App\Repositories\ActivityRepository;
use App\Services\ActivityService;
use DB;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ActivityController extends Controller
{
    const MEDIA_COLLECTION = 'activity';

    private ActivityService $service;

    private ActivityRepository $repository;

    public function __construct(ActivityService $service, ActivityRepository $repository)
    {
        $this->middleware('auth');

        $this->service = $service;

        $this->repository = $repository;
	}

    public function index(Request $request)
    {
    	$activities = $this->repository->getAll($request->term);
   
    	return view('activity.index', compact('activities'));
    }

    public function create()
    {
        $pets = Pet::all();

        return view('activity.create', compact('pets'));
    }

    public function store(ActivityRequest $request) 
    {
        DB::beginTransaction();

        try {

            $this->service->store($request);
            
            DB::commit();

            return redirect()
                ->route('activity_index')
                ->with('success', 'Atividade cadastrada com sucesso!');

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('activity_index')
                ->with('error', 'Ocorreu um erro ao salvar a atividade!');
        }
    }

    public function show($id)
    {
        $activity = $this->repository->getById($id);

    	return view('activity.show', compact('activity'));
    }

    public function edit($id)
    {
        $activity = $this->repository->getById($id);

        $pets = Pet::all();

        return view('activity.edit', [
            'activity' => $activity, 
            'pets' => $pets
        ]);
    }

    public function update(ActivityRequest $request, $id)
    {
        try {

            $this->service->update($request, $id);            
            
            DB::commit();

            return redirect()
                ->route('activity_index')
                ->with('success', 'Atividade editada com sucesso!');

        } catch (\Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('activity_index')
                ->with('error', 'Ocorreu um erro ao editar a atividade!');
        }
    }
    
    public function destroy($id)
    {        
        DB::beginTransaction();
            
        try {
                                
            $this->service->delete($id);
            
            DB::commit();

            return redirect()
                ->route('activity_index')
                ->with('success', 'Atividade removida com sucesso!'); 
            
        } catch(\Exception $e) {
            
            DB::rollback();

            return redirect()
                ->route('activity_index')
                ->with('error', 'Ocorreu um ao tentar excluir a atividade!'); 
        }
    }

    public function destroyMedia($id) 
    {
        $media = Media::findOrFail($id);

        DB::beginTransaction();

        try {

            $media->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Arquivo da atividade removida com sucesso!'
            ], 200);

        } catch(\Exception $e) {
            
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um ao tentar excluir a arquivo da atividade!'
            ], 400);            
        }
    }
}
