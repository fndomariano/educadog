<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
