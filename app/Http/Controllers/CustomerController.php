<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return view('customers.index');
    }

    public function create()
    {
        return view('customers.add');
    }

    public function _fetch_customer(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $search = $request->get('search', '');
        $sort   = $request->get('sort', 'id');
        $order  = $request->get('order', 'asc');

        $query = Customer::with('user:id,name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sort, $order);

        $total = $query->count();

        $rows = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'total' => $total,
            'rows'  => $rows,
        ]);
    }

    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'customer_name'    => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_phone'   => $request->customer_phone,
                'user_id'          => Auth::user()->id
            ]);

            DB::commit();

            return $this->successResponse($customer);
        } catch (\Throwable $th) {
            Log::info('Error create data pelanggan : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    public function edit($id)
    {
        $current = Customer::find($id);

        return view('customers.edit', compact('current'));
    }

    public function update(CustomerRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::where('id', $id)->update([
                'customer_name'    => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_phone'   => $request->customer_phone
            ]);
            DB::commit();

            return $this->successResponse($customer);
        } catch (\Throwable $th) {
            Log::info('Error update data pelanggan : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $current = Customer::find($id);
            $current->delete();
            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error hapus data pelanggan : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }
}
