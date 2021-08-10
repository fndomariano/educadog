<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Pet;
use App\Http\Requests\ActivityRequest;
use DB;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ActivityController extends Controller
{
    const MEDIA_COLLECTION = 'activity';

    public function __construct()
    {
        $this->middleware('auth');
	}

    public function index(Request $request)
    {
        $term = $request->term;

    	$activities = Activity::query()
    		->orWhere('description', 'LIKE', '%'.$term.'%')    		
    		->paginate(10);
   
    	return view('activity.index', compact('activities'));
    }

    public function create()
    {
        $pets = Pet::all();

        return view('activity.create', compact('pets'));
    }

    public function store(ActivityRequest $request) {
        
        DB::beginTransaction();

        try {

            $date = \DateTime::createFromFormat('d/m/Y', $request->activity_date);

            $activity = new Activity;
            $activity->activity_date = $date->format('Y-m-d');            
            $activity->pet_id = (int) $request->pet_id;
            $activity->score = (int) $request->score;
            $activity->description = $request->description;

            $files = $request->file('files');
            
            if ($files) {
                foreach ($files as $file) {
                    $activity->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
                }
            }
        
            $activity->save();
            
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
        $activity = Activity::find($id);

    	if (!$activity) {
			abort(404);
		}

    	return view('activity.show', compact('activity'));
    }

    public function edit($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            abort(404);
        }

        $pets = Pet::all();

        return view('activity.edit', [
            'activity' => $activity, 
            'pets' => $pets
        ]);
    }

    public function update(ActivityRequest $request, $id)
    {
        try {

            $date = \DateTime::createFromFormat('d/m/Y', $request->activity_date);
            
            $activity = Activity::find($id);
            $activity->activity_date = $date->format('Y-m-d');
            $activity->pet_id = (int) $request->pet_id;
            $activity->score = (int) $request->score;
            $activity->description = $request->description;

            $files = $request->file('files');
            
            if ($files) {
                foreach ($files as $file) {
                    $activity->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
                }
            }

            $activity->save();
            
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
        $activity = Activity::find($id);

        if (!$activity) {
            abort(404);
        }

        DB::beginTransaction();
            
        try {
                                
            $activity->delete();
            
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

    public function destroyMedia($id) {
        
        $media = Media::find($id);

        try {

            $media->delete();

            return response()->json([
                'sucess' => true,
                'message' => 'Arquivo da atividade removida com sucesso!'
            ], 204);

        } catch(\Exception $e) {
            
            DB::rollback();

            return response()->json([
                'sucess' => false,
                'message' => 'Ocorreu um ao tentar excluir a arquivo da atividade!'
            ], 400);            
        }
    }
}
