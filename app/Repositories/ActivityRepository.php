<?php

namespace App\Repositories;

use App\Models\Activity;

class ActivityRepository
{
    private $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function getAll($term)
    {
        return $this->activity
            ->query()
            ->where('description', 'LIKE', '%' . $term . '%')
            ->paginate(10);
    }

    public function getActivitiesByAndDate($id, $startDate, $endDate)
    {        
        return $this->activity
            ->query()
            ->select(['id', 'activity_date', 'score'])
            ->where('pet_id', '=', $id)
            ->whereBetween('activity_date', [$startDate, $endDate])
            ->get();
    }

    public function getById($id)
    {
        $data = $this->activity->findOrFail($id);
        $data->pet->customer;
        $media = $data->getMedia(\App\Services\ActivityService::MEDIA_COLLECTION);
        $data->toArray();

        foreach ($media as $index => $value) {
            
            $data['media'][$index] = [
                'id'   => $value->id,
                'type' => $value->type,
                'url'  => $value->getUrl()
            ];
        }
    
        return $data;
    }
}
