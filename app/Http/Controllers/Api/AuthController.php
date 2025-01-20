<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(AuthRequest $request)
    {
        DB::beginTransaction();
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->authencitaionFailed(null);
            } else {
                $user = User::where('email', $request['email'])->firstOrFail();

                $token = $user->createToken('auth_token')->plainTextToken;

                $result = [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'role'         => $user->roles->first()->name,
                    'access_token' => $token
                ];

                DB::commit();

                return $this->successResponse($result);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorResponse($th->getMessage());
        }
    }

    public function signOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout success'
        ], 200);
    }
}
