<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Auth;
use App\Models\User;
use Hash;
use Illuminate\Support\Arr;
use DB;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:list-user|create-user|edit-user|delete-user', ['only' => ['index','store']]);
        $this->middleware('permission:create-user', ['only' => ['create','store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $auth  = auth()->user()->with('permissions')->first();
        $data  = User::all();
        if($request->ajax()){
            return DataTables::of($data)
                ->addColumn('name', function($row){
                    return $row->name;
                })
                ->addColumn('email', function($row){
                    return $row->email;
                })
                ->addColumn('role', function($row){
                    if (!empty($row->getRoleNames())) {
                        foreach ($row->getRoleNames() as $role) {
                            if($role == 'SUPER-ADMIN'){
                                $badge = '<span class="badge badge-danger text-white">'.$role.'</span>';
                                return $badge;
                            }
                            else{
                                $badge = '<span class="badge badge-info text-white">'.$role.'</span>';
                                return $badge;
                            }
                        }
                    }
                })
                ->addColumn('status', function($row){
                    if($row->status == '0'){
                        $badge = '<span class="badge badge-info text-white">Belum Ativasi</span>';
                        return $badge;
                    }
                    elseif($row->status == '1'){
                        $badge = '<span class="badge badge-success text-white">Aktif</span>';
                        return $badge;
                    }
                    elseif($row->status == '2'){
                        $badge = '<span class="badge badge-warning text-white">Tidak Aktif</span>';
                        return $badge;
                    }
                    else{
                        $badge = '<span class="badge badge-danger text-white">Blokir</span>';
                        return $badge;
                    }
                })
                ->addColumn('action', function($row)use($auth){
                    $button  = '';
                    if ($auth->can('edit-user')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="'.route('users.edit',$row->id).'" class="btn btn-sm btn-warning btn-icon btn-round"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';    
                    }
                    if ($auth->can('delete-user')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button onclick="deleteItem(this)" data-name="'.$row->name.'" data-id="'.$row->id.'" class="btn btn-sm btn-danger btn-icon btn-round"><i class="fas fa-trash"></i></button>';    
                    }
                    if ($button == '') {
                        $button = 'User does not have the right permissions.';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'role','status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.users.index');
    }

    public function create(){

        return view('pages.users.create');
    }

    public function store(Request $request){

    }

    public function edit($id){

        return view('pages.users.edit');
    }

    public function update(Request $request){

    }

    public function destroy(Request $request){

    }
}
