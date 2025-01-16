<?php

namespace App\Http\Controllers;

use App\Models\PermissionParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('roles.index');
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
    public function store(Request $request)
    {
        //
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
        $currentRole        = Role::find($id);
        $permissionParents  = PermissionParent::with('permissions')->get();
        $selectedPermission = DB::table('role_has_permissions')
                                  ->where('role_has_permissions.role_id', $id)
                                  ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                                  ->all();

        return view('roles.edit', compact('currentRole', 'permissionParents', 'selectedPermission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);

            $permissionsID = array_map(
                function ($value) {
                    return (int) $value;
                },
                $request->input('permission')
            );

            $role->syncPermissions($permissionsID);
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Hak akses berhasil diupdate'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'Whoops! something wen"\t wrong dude!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function _fetch_data(Request $request)
    {
        $limit  = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $sort   = $request->get('sort', 'id');
        $order  = $request->get('order', 'asc');

        $query = Role::latest();

        $query->orderBy($sort, $order);

        $total = $query->count();

        $rows = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'total' => $total,
            'rows'  => $rows,
        ]);
    }
}
