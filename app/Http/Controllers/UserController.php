<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Auth;
use App\Models\User;
use Hash;
use Illuminate\Support\Arr;
use DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // function __construct()
    // {
    //     // $this->middleware('permission:list-user|create-user|edit-user|delete-user', ['only' => ['index','store']]);
    //     // $this->middleware('permission:create-user', ['only' => ['create','store']]);
    //     // $this->middleware('permission:edit-user', ['only' => ['edit','update']]);
    //     // $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    // }

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
        $roles  = Role::all();
        return view('pages.users.create', compact(
            'roles'
        ));
    }

    public function store(Request $request){
        dd($request->all());
        $request->validate([
            'name'      => 'required|string|max:200',
            'email'     => 'required|email',
            'no_induk'  => 'required',
            'alamat'    => 'required',
            'telepon'    => 'required',
            'jenkel'    => 'required',
            'tgl_lahir' => 'required',
            'tmpt_lahir'=> 'required',
            'password'  => 'required',
            'role'      => 'required',
        ],[
            'name.required'      => 'Nama wajib diisi',
            'name.string'        => 'Nama harus berupa string',
            'name.max'           => 'Nama tidak boleh lebih dari 200 karakter',
            'email.required'     => 'Email wajib diisi',
            'email.email'        => 'Email tidak valid',
            'no_induk.required'  => 'No induk harus diisi',
            'alamat.required'    => 'Alamat harus diisi',
            'telepon.required'    => 'Telepon harus diisi',
            'jenkel.required'    => 'Jenis kelamin harus diisi',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi',
            'tmpt_lahir.required'=> 'Tempat lahir harus diisi',
            'password.required'  => 'Password wajib diisi',
            'role.required'      => 'Role wajib diisi',
        ]);

        $data                   = $request->all();
        $data['password']       = Hash::make($data['password']);
        $user                   = User::create($data);
        $user->assignRole($request->input('roles'));

        if($user){
            return response()->json([
                'code'      => 200,
                'message'   => 'User berhasil di simpan'
            ]);
        }

        return response()->json([
            'code'      => 400,
            'message'   => 'User gagal di simpan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:200',
            'email'     => 'required|email',
            'no_induk'  => 'required',
            'alamat'    => 'required',
            'telepon'   => 'required',
            'jenkel'    => 'required',
            'tgl_lahir' => 'required',
            'tmpt_lahir'=> 'required',
            'password'  => 'required',
            'role'      => 'required',
        ]);

        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            $data = Arr::except($data, ['password']);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found'
            ]);
        }

        $user->update($data);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('role'));

        return response()->json([
            'code' => 200,
            'message' => 'User berhasil di update'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found'
            ]);
        }

        $user->delete();
        return response()->json([
            'code' => 200,
            'message' => 'User berhasil di hapus'
        ]);
    }
}



