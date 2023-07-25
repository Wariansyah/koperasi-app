<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:role-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {
                    return $row->permissions->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('roles.edit', $row->id) . '" class="btn btn-sm btn-info">Edit</a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.roles.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission  = Permission::all();
        return view('pages.roles.create', compact(
            'permission'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama tidak boleh lebih dari 200 karakter',
            'permission.required' => 'permission wajib diisi',
        ]);


        $data = $request->all();
        $role = Role::create($data);
        $role->syncPermissions($request->input('permission'));
        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role berhasil di simpan');
        }


        return response()->json([
            'code' => 400,
            'message' => 'Role gagal di simpan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('pages.roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('pages.roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required|array',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.string' => 'Nama harus berupa string',
            'name.max' => 'Nama tidak boleh lebih dari 200 karakter',
            'permission.required' => 'Permission wajib diisi',
            'permission.array' => 'Permission harus berupa array',
        ]);

        $data = $request->all();
        $role = Role::findOrFail($id);
        $role->update($data);
        $role->syncPermissions($request->input('permission'));

        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate');
        }

        return response()->json([
            'code' => 400,
            'message' => 'Role gagal diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Role berhasil dihapus'
            ]);
        }

        return response()->json([
            'code' => 400,
            'message' => 'Role gagal dihapus'
        ]);
    }
}
