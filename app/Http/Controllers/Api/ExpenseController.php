<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest;
use App\Models\Expense;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user()->id;

        $tanggal = $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d') ?? Carbon::today();
        $jenis   = $request->jenis ?? 'all';

        $data = Expense::with('user:id,name')
                    ->whereHas('user', function ($query) use ($auth) {
                        $query->where('id', $auth);
                    })
                    ->where('tanggal_pencatatan', $tanggal);

        if ($jenis !== 'all') {
            $data->where('jenis_pengeluaran', $jenis);
        }

        $result = $data->orderBy('id', 'desc')->get();

        return $this->successResponse($result);
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
    public function store(ExpensesRequest $request)
    {
        DB::beginTransaction();
        try {
            $expense = Expense::create([
                'tanggal_pencatatan'  => $request->tanggal_pencatatan,
                'jenis_pengeluaran'   => $request->jenis_pengeluaran,
                'jumlah_pengeluaran'  => $request->jumlah_pengeluaran,
                'catatan_pengeluaran' => isset($request->catatan_pengeluaran) ? $request->catatan_pengeluaran : '',
                'created_user'        => Auth::user()->name,
                'updated_user'        => Auth::user()->name,
                'user_id'             => Auth::user()->id
            ]);

            DB::commit();

            return $this->successResponse($expense);
        } catch (\Throwable $th) {
            Log::info('Error insert pengeluaran : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $current = Expense::with('user:id,name')->where('id', $id)->first();

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
    public function update(ExpensesRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $expense = Expense::where('id', $id)->update([
                'tanggal_pencatatan'  => $request->tanggal_pencatatan,
                'jenis_pengeluaran'   => $request->jenis_pengeluaran,
                'jumlah_pengeluaran'  => $request->jumlah_pengeluaran,
                'catatan_pengeluaran' => isset($request->catatan_pengeluaran) ? $request->catatan_pengeluaran : '',
                'updated_at'          => Carbon::now()
            ]);

            DB::commit();

            return $this->successResponse($expense);
        } catch (\Throwable $th) {
            Log::info('Error update pengeluaran : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            Expense::where('id', $id)->delete();

            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error hapus pengeluaran : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong');
        }
    }
}
