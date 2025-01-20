<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with('user:id,name')
                    ->where('user_id', Auth::user()->id)
                    ->latest()
                    ->get();

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
    public function store(ProductCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $produk = Product::create([
                'product_name'  => $request->product_name,
                'product_price' => $request->product_price,
                'product_note'  => isset($request->product_note) ? $request->product_note : '',
                'user_id'       => Auth::user()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

            DB::commit();

            return $this->successResponse($produk);
        } catch (\Throwable $th) {
            Log::info('Error saat create data produk : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $current = Product::with('user:id,name')->where('id', $id)->first();

        return $this->successResponse($current);
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
    public function update(ProductCreateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $produk = Product::where('id', $id)->update([
                'product_name'  => $request->product_name,
                'product_price' => $request->product_price,
                'product_note'  => $request->product_note,
                'updated_at'    => Carbon::now()
            ]);

            DB::commit();

            return $this->successResponse($produk);
        } catch (\Throwable $th) {
            Log::info('Error saat update data produk : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            Product::where('id', $id)->delete();

            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error saat hapus data produk : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }
}
