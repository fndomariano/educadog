<?php

namespace App\Services;

use App\Models\Activity;
use App\Http\Requests\ActivityRequest;
use App\Repositories\ActivityRepository;

class ActivityService
{
    const MEDIA_COLLECTION = 'activity';

    private $repository;

    public function __construct(ActivityRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function store(ActivityRequest $request)
    {
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
    }

    public function update(ActivityRequest $request, $id)
    {
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
    }

    public function delete($id)
    {
        $activity = $this->repository->getById($id);

        $activity->delete();
    }
}
