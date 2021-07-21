<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Http\Requests\ActivityRequest;
use DB;
use Illuminate\Http\Request;

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

    public function show($id)
    {
        $activity = Activity::find($id);

    	if (!$activity) {
			abort(404);
		}

    	return view('activity.show', compact('activity'));
    }
    
}
