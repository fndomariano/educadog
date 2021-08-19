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
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->paginate(10);
    }

    public function getCustomerByEmail($email)
    {
        return $this->customer
            ->query()            
            ->where('email', '=', $email)
            ->first();
    }

    public function getById($id)
    {
        return $this->customer->findOrFail($id);
    }
}
