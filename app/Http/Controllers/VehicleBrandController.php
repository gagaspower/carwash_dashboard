<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleBrandRequest;
use App\Models\VehicleBrand;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleBrandController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('merk_kendaraan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('merk_kendaraan.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleBrandRequest $request)
    {
        DB::beginTransaction();
        try {
            $customer = VehicleBrand::create([
                'brand_name' => $request->brand_name
            ]);

            DB::commit();

            return $this->successResponse($customer);
        } catch (\Throwable $th) {
            Log::info('Error create data merk kendaraan : ' . json_encode($th->getMessage()));
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
        $current = VehicleBrand::find($id);

        return view('merk_kendaraan.edit', compact('current'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleBrandRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $customer = VehicleBrand::where('id', $id)->update([
                'brand_name' => $request->brand_name
            ]);

            DB::commit();

            return $this->successResponse($customer);
        } catch (\Throwable $th) {
            Log::info('Error update data merk kendaraan : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $current = VehicleBrand::find($id);
            $current->delete();
            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error hapus data merk : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    public function _fetch_data(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $search = $request->get('search', '');
        $sort   = $request->get('sort', 'id');
        $order  = $request->get('order', 'asc');

        $query = VehicleBrand::latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('brand_name', 'like', "%{$search}%");
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
}
