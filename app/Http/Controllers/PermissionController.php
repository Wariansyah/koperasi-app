<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Unique;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DataTables;

class PermissionController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::with('roles')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name; // Ubah 'name' sesuai dengan kolom yang tepat di tabel "permissions"
                })
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('permissions.edit', $row->id) . '" class="btn btn-sm btn-info">Edit</a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)">Delete</button>';
                    return $btn;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.permissions.index');
    }


    public function create()
    {
        $permissions = Permission::all();
        return view('pages.permissions.create', compact('permissions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ], [
            'name.required' => 'Nama Permission wajib diisi',
            'name.unique' => 'The name has already been taken.',
        ]);
    
        Permission::create([
            'name' => $request->name,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dibuat'
        ]);
    }
    

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.show', compact('user'));
    }
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('pages.permissions.edit', compact('permission'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ], [
            'name.required' => 'Nama Permission wajib diisi',
            'name.unique' => 'The name has already been taken.',
        ]); 
        
        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');
        $permission->save();

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dibuat'
        ]);
    }


    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        if ($permission->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Permission berhasil dihapus'
            ]);
        }

        return response()->json([
            'code' => 400,
            'message' => 'Permission gagal dihapus'
        ]);
    }
}