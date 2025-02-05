<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\PegawaiCreateRequest;
use App\Http\Requests\PegawaiUpdateRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
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
            $input = $request->all();

            $cekEmail = User::where('email', $input['email'])->first();

            if ($cekEmail) {
                return $this->errorValidateResponse(null, 'Email sudah terdaftar!');
            }

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

    public function show($id)
    {
        $current = User::find($id);

        return $this->successResponse($current);
    }

    public function update(PegawaiUpdateRequest $request, $id)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $input = $request->all();

            $cekEmail = User::where('email', $input['email'])->where('id', '!=', $id)->first();

            if ($cekEmail) {
                return $this->errorValidateResponse(null, 'Email sudah terdaftar!');
            }

            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }

            $user = User::where('id', $id)->update($input);
            DB::commit();

            return $this->successResponse($user);

            DB::commit();

            return $this->successResponse($user);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorResponse($th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $current = User::find($id);
            $current->delete();
            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }
}
