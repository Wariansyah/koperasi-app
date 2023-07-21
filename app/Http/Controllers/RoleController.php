<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use DataTables;
use Auth;

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $roles = Role::all();
        $auth  = auth()->user()->with('permissions')->first();
        if($request->ajax()){
            return DataTables::of($data)
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('role', function($row){
                    if($role == 'SUPER-ADMIN'){
                        $badge = '<span class="badge badge-danger text-white">'.$role.'</span>';
                        return $badge;
                    }
                    else{
                        $badge = '<span class="badge badge-info text-white">'.$role.'</span>';
                        return $badge;
                    }
                })
                ->addColumn('action', function($row)use($auth){
                    $button  = '';
                    if ($auth->can('edit-role')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('roles.edit',$row->id).'" class="btn btn-sm btn-warning btn-icon btn-round"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';    
                    }
                    if ($auth->can('delete-role')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button onclick="deleteItem(this)" data-name="'.$row->name.'" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-icon btn-round"><i class="fas fa-trash"></i></button>';    
                    }
                    if ($button == '') {
                        $button = 'User does not have the right permissions.';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'role'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('roles.index',compact('roles'));

        return view('pages.roles.index');
    }

    public function create(){

        $permission = Permission::get();
        return view('pages.roles.create',compact('permission'));
    }

    public function store(Request $request){

        $this->validate($request, [
            'name'          => 'required|unique:roles,name',
            'permission'    => 'required',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);
        $role->syncPermissions($request->input('permission'));

        if($role){
            return response()->json([
                'code'      => 200,
                'message'   => 'Role berhasil ditambahkan'
            ]);
        }

        return response()->json([
            'code'      => 400,
            'message'   => 'Role gagal ditambahkan'
        ]);
    }

    public function edit($id){

        $role               = Role::find($id);
        $permission         = Permission::get();
        $rolePermissions    = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();
        return view('pages.roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request){

    }

    public function destroy(Request $request){

    }
}
