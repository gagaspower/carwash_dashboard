<?php

namespace App\Http\Controllers;

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
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.add');
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
                'product_note'  => $request->product_note,
                'user_id'       => Auth::user()->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

            DB::commit();

            return $this->successResponse($produk);
        } catch (\Throwable $th) {
            Log::info('Error saat create data produk : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $current = Product::find($id);

        return view('products.edit', compact('current'));
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

    public function _fetch_data(Request $request)
    {
        $user_id = Auth::user()->id;
        $limit   = $request->get('limit', 10);
        $offset  = $request->get('offset', 0);
        $search  = $request->get('search', '');
        $sort    = $request->get('sort', 'id');
        $order   = $request->get('order', 'asc');

        $query = Product::with('user:id,name')->where('user_id', $user_id)->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sort, $order);

        $total = $query->count();

        $rows = $query->skip($offset)->take($limit)->get()->map(fn($result) => [
            'id'            => $result->id,
            'product_name'  => $result->product_name,
            'product_price' => 'Rp ' . number_format($result->product_price),
            'product_note'  => $result->product_note,
            'user'          => $result->user->name
        ]);

        return response()->json([
            'total' => $total,
            'rows'  => $rows,
        ]);
    }
}
