<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Customer::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return $this->successResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            $input            = $request->all();
            $input['user_id'] = Auth::user()->id;

            if (!empty($request->customer_address)) {
                $input['customer_address'] = $request->customer_address;
            }

            $insert = Customer::create($input);

            DB::commit();

            return $this->successResponse($insert);
        } catch (\Throwable $th) {
            Log::info('Error insert customer : ' . json_encode($th->getMessage()));

            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Customer::find($id);

        if (!$detail) {
            return $this->notFoundResponse('Data pelanggan tidak ditemukan');
        }

        return $this->successResponse($detail);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();

            if (!empty($request->customer_address)) {
                $input['customer_address'] = $request->customer_address;
            }

            $insert = Customer::where('id', $id)->update($input);

            DB::commit();

            return $this->successResponse($insert);
        } catch (\Throwable $th) {
            Log::info('Error update customer : ' . json_encode($th->getMessage()));

            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $detail = Customer::find($id);

            if (!$detail) {
                return $this->notFoundResponse('Data pelanggan tidak ditemukan');
            }

            $detail->delete();

            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error hapus customer : ' . json_encode($th->getMessage()));

            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong.');
        }
    }
}
