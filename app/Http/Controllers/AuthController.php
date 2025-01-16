<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
