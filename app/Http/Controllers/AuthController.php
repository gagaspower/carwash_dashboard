<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    public function _create_session(AuthRequest $request)
    {
        DB::beginTransaction();
        try {
            $credentials = $request->validated();
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Akun tidak ditemukan!',
                ], 401);
            }

            $request->session()->regenerate();
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Login berhasil',
            ], 200);
        } catch (\Throwable $th) {
            Log::info('Login gagal : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Whoops! something wen\"t wrong dude!',
            ], 500);
        }
    }

    public function _register()
    {
        return view('auth.register');
    }

    public function store_register(UserCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $input             = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            DB::commit();

            return $this->successResponse($user);
        } catch (\Throwable $th) {
            Log::info('Error add new user : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
