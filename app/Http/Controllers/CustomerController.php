<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerService $service;

    private CustomerRepository $repository;

    public function __construct(CustomerService $service, CustomerRepository $repository)
    {
        $this->middleware('auth');

        $this->service = $service;

        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $customers = $this->repository->getAll($request->term);
   
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->service->store($request);

            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um erro ao salvar o cliente!');
        }
    }

    public function show($id)
    {
        $customer = $this->repository->getById($id);

        return view('customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = $this->repository->getById($id);

        return view('customer.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, $id)
    {
        try {
            $this->service->update($request, $id);
            
            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente editado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um erro ao editar o cliente!');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
            
        try {
            $this->service->delete($id);
            
            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um ao tentar excluir o cliente!');
        }
    }
}
