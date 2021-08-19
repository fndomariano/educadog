<?php

namespace App\Services;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    private const MEDIA_COLLECTION = 'customers';

    private $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(CustomerRequest $request)
    {
        $customer         = new Customer();
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

    public function createPassword(array $data)
    {
        $customer = $this->validateCustomer($data['email']);     
            
        if ($customer->password != null && $customer->password != "") {
            throw new \Exception('Você já possui uma senha cadastrada!', 419);
        }

        $customer->password = Hash::make($data['password']);
        $customer->save();
    }

    public function authenticate(array $data)
    {
        $customer = $this->validateCustomer($data['email']);
        
        if (!$customer || !Hash::check($data['password'], $customer->password)) {
            throw new \Exception('E-mail ou senha inválidos', 400);
        }
        
        if (!$token = auth('api')->attempt($data)) {            
            throw new \Exception('Acesso não autorizado!', 401);
        }
        
        return $token;
    }

    public function unauthenticate($token)
    {
        if ($token == null || $token == "") {
            throw new \Exception('Token precisa ser informado!', 400);
        }

        auth('api')->logout();        
    }

    private function validateCustomer($email) 
    {
        $customer = $this->repository->getCustomerByEmail($email);

        if (!$customer) {
            throw new \Exception('Conta não encontrada. Verifique se os dados fornecidos estão corretos!', 404);
        }

        if (!$customer->active) {
            throw new \Exception('O seu perfil está inativo!', 401);
        }

        return $customer;
    }
}
