<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.add', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
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
        $roles        = Role::pluck('name', 'name')->all();
        $current      = User::find($id);
        $selectedRole = $current->roles->pluck('name', 'name')->first();

        return view('users.edit', compact('roles', 'current', 'selectedRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }

            $user = User::find($id);
            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $id)->delete();

            $user->assignRole($request->input('roles'));
            DB::commit();

            return $this->successResponse($user);
        } catch (\Throwable $th) {
            Log::info('Error update user : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something when wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $current = User::find($id);
            $current->delete();
            DB::commit();

            return $this->successResponse(null);
        } catch (\Throwable $th) {
            Log::info('Error hapus user : ' . json_encode($th->getMessage()));
            DB::rollBack();

            return $this->errorResponse('Whoops! something wrong dude');
        }
    }

    public function _fetch_user(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $search = $request->get('search', '');
        $sort   = $request->get('sort', 'id');
        $order  = $request->get('order', 'asc');

        $query = User::with('model_has_role.role');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sort, $order);

        $total = $query->count();

        $rows = $query->skip($offset)->take($limit)->get()->map(fn($result) => [
            'id'    => $result->id,
            'name'  => $result->name,
            'email' => $result->email,
            'role'  => $result->model_has_role->role->name
        ]);

        return response()->json([
            'total' => $total,
            'rows'  => $rows,
        ]);
    }
}
