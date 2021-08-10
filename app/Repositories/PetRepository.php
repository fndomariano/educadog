<?php

namespace App\Repositories;

use App\Models\Pet;

class PetRepository 
{
    private $pet;

    public function __construct(Pet $pet)
    {
        $this->pet = $pet;
    }

    public function getAll($term)
    {
    	return $this->pet
            ->query()
    		->where('name', 'LIKE', '%'.$term.'%')
    		->paginate(10);
    }

    public function getById($id)
    {
        return $this->pet->findOrFail($id);
    }

}