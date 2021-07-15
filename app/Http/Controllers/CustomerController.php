<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
	}

    public function index(Request $request)
    {
        $term = $request->term;

    	$customers = Customer::query()
    		->orWhere('name', 'LIKE', '%'.$term.'%')
    		->orWhere('email', 'LIKE', '%'.$term.'%')
    		->orWhere('phone', 'LIKE', '%'.$term.'%')
    		->paginate(10);
   
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

            $customer = new Customer;
            $customer->name   = $request->name;
            $customer->email  = $request->email;
            $customer->phone  = $request->phone;
            $customer->save();
            
            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente cadastrado com sucesso!');

        } catch (Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um erro ao salvar o cliente!');
        }
    }

    public function show($id)
    {
        $customer = Customer::find($id);

    	if (!$customer) {
			abort(404);
		}

    	return view('customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            abort(404);
        }

        return view('customer.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, $id)
    {
        try {

            $customer = Customer::find($id);
            $customer->name   = $request->name;
            $customer->email  = $request->email;
            $customer->phone  = $request->phone;
            $customer->save();
            
            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente editado com sucesso!');

        } catch (Exception $e) {
            
            DB::rollback();
            
            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um erro ao editar o cliente!');
        }
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            abort(404);
        }

        DB::beginTransaction();
            
        try {
                                
            $customer->delete();
            
            DB::commit();

            return redirect()
                ->route('customer_index')
                ->with('success', 'Cliente removido com sucesso!'); 
            
        } catch(Exception $e) {
            
            DB::rollback();

            return redirect()
                ->route('customer_index')
                ->with('error', 'Ocorreu um ao tentar excluir o cliente!'); 
        }
    }
}
