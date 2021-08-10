<?php

namespace App\Services;

use App\Models\Pet;
use App\Http\Requests\PetRequest;
use App\Repositories\PetRepository;

class PetService 
{
    const MEDIA_COLLECTION = 'pets';

    private $repository;

    public function __construct(PetRepository $repository) 
    {
        $this->repository = $repository;
    }

    public function store(PetRequest $request)
    {
        $pet = new Pet;
        $pet->name  = $request->name;
        $pet->breed = $request->breed;
        $pet->customer_id = (int) $request->customer_id;
        $pet->active = $request->active == 'on';
        
        $file = $request->file('photo');
        
        if ($file) {
            $pet->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
        }

        $pet->save();
    }

    public function update(PetRequest $request, $id)
    {
        $pet = $this->repository->getById($id);
        $pet->name  = $request->name;
        $pet->breed = $request->breed;            
        $pet->customer_id = (int) $request->customer_id;
        $pet->active = $request->active == 'on';

        $file = $request->file('photo');
        $media = $pet->getMedia(self::MEDIA_COLLECTION);

        if ($file && isset($media[0])) {
            $media[0]->delete();
        }

        if ($file) {
            $pet->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
        }

        $pet->save();
    }

    public function delete($id)
    {
        $pet = $this->repository->getById($id);

        $pet->delete();
    }
}