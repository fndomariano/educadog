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

    public function getById($id)
    {
        return $this->activity->findOrFail($id);
    }
}
