<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getAll($term)
    {
        return $this->customer
            ->query()
            ->orWhere('name', 'LIKE', '%'.$term.'%')
            ->orWhere('email', 'LIKE', '%'.$term.'%')
            ->orWhere('phone', 'LIKE', '%'.$term.'%')
            ->paginate(10);
    }

    public function getById($id)
    {
        return $this->customer->findOrFail($id);
    }
}
