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
            ->where('name', 'LIKE', '%' . $term . '%')
            ->paginate(10);
    }

    public function getMyPets($customerId)
    {
        $myPets = $this->pet
            ->query()
            ->select(['id', 'name', 'breed', 'active'])
            ->where('customer_id', '=', $customerId)
            ->get();

        foreach ($myPets as $pet) {
            $media = $pet->getFirstMedia(\App\Services\PetService::MEDIA_COLLECTION);
            $pet['photo'] = $media == null ? null : $media->getUrl();
            unset($pet['media']);
        }

        return $myPets;
    }

    public function getById($id)
    {
        return $this->pet->findOrFail($id);
    }
}
