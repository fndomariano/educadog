<?php

namespace App\Services;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;

class CustomerService 
{
    const MEDIA_COLLECTION = 'customers';

    private $repository;

    public function __construct(CustomerRepository $repository) 
    {
        $this->repository = $repository;
    }
    
    public function store(CustomerRequest $request) 
    {
        $customer         = new Customer;
        $customer->name   = $request->name;
        $customer->email  = $request->email;
        $customer->phone  = $request->phone;
        $customer->active = $request->active == 'on';
        
        $file = $request->file('contract');
        
        if ($file) {
            $customer->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
        }

        $customer->save();
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer         = $this->repository->getById($id);
        $customer->name   = $request->name;
        $customer->email  = $request->email;
        $customer->phone  = $request->phone;
        $customer->active = $request->active == 'on'; 

        $file = $request->file('contract');
        $media = $customer->getMedia(self::MEDIA_COLLECTION);

        if ($file && isset($media[0])) {
            $media[0]->delete();
        }

        if ($file) {
            $customer->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION);
        }

        $customer->save();
    }

    public function delete($id)
    {
        $customer = $this->repository->getById($id);

        $customer->delete();
    }
}