<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\PegawaiCreateRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class PegawaiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $query = User::where('owner_id', Auth::user()->id);

        $rows = $query->get()->map(fn($result) => [
            'id'    => $result->id,
            'name'  => $result->name,
            'email' => $result->email,
            'role'  => $result->roles->first()->name ?? ''
        ]);

        return $this->successResponse($rows);
    }

    public function store(PegawaiCreateRequest $request)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $input             = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['owner_id'] = Auth::user()->id;

            $user = User::create($input);
            $user->assignRole('Pegawai');
            DB::commit();

            return $this->successResponse($user);

            DB::commit();

            return $this->successResponse($user);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorResponse($th->getMessage());
        }
    }
}
