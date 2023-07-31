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

    public function index(Request $request)
    {

        $auth  = auth()->user()->with('permissions')->first();
        $data  = User::all();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('role', function ($row) {
                    if (!empty($row->getRoleNames())) {
                        foreach ($row->getRoleNames() as $role) {
                            if ($role == 'SUPER-ADMIN') {
                                $badge = '<span class="badge badge-danger text-white">' . $role . '</span>';
                                return $badge;
                            } else {
                                $badge = '<span class="badge badge-info text-white">' . $role . '</span>';
                                return $badge;
                            }
                        }
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == '0') {
                        $badge = '<span class="badge badge-info text-white">Belum Ativasi</span>';
                        return $badge;
                    } elseif ($row->status == '1') {
                        $badge = '<span class="badge badge-success text-white">Aktif</span>';
                        return $badge;
                    } elseif ($row->status == '2') {
                        $badge = '<span class="badge badge-warning text-white">Tidak Aktif</span>';
                        return $badge;
                    } else {
                        $badge = '<span class="badge badge-danger text-white">Blokir</span>';
                        return $badge;
                    }
                })
                ->addColumn('action', function ($row) use ($auth) {
                    $button  = '';
                    if ($auth->can('edit-user')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-warning btn-icon btn-round"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    }
                    if ($auth->can('delete-user')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<button onclick="deleteItem(this)" data-name="' . $row->name . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-icon btn-round"><i class="fas fa-trash"></i></button>';
                    }
                    if ($button == '') {
                        $button = 'User does not have the right permissions.';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'role', 'status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.users.index');
    }

    public function create()
    {
        $roles  = Role::all();
        return view('pages.users.create', compact(
            'roles'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role' => 'required',
                'no_induk' => 'required|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'jenkel' => 'nullable|in:Laki-laki,Perempuan',
                'tmpt_lahir' => 'nullable|string|max:100',
                'tgl_lahir' => 'nullable|date',
                'telepon' => 'nullable|string|max:20',
            ], [
                'name.required' => 'Nama wajib diisi',
                'name.string' => 'Nama harus berupa string',
                'name.max' => 'Nama tidak boleh lebih dari 200 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password harus minimal 6 karakter',
                'role.required' => 'Role wajib diisi',
                'no_induk.required' => 'No Induk wajib diisi',
                'no_induk.string' => 'No Induk harus berupa string',
                'no_induk.max' => 'No Induk tidak boleh lebih dari 20 karakter',
                'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',
                'jenkel.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
                'tmpt_lahir.max' => 'Tempat lahir tidak boleh lebih dari 100 karakter',
                'tgl_lahir.date' => 'Tanggal lahir harus dalam format tanggal yang valid',
                'telepon.max' => 'Telepon tidak boleh lebih dari 20 karakter',
            ]);

            // Simpan data user
            $user = new User([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'no_induk' => $request->input('no_induk'),
                'alamat' => $request->input('alamat'),
                'jenkel' => $request->input('jenkel'),
                'tmpt_lahir' => $request->input('tmpt_lahir'),
                'tgl_lahir' => $request->input('tgl_lahir'),
                'telepon' => $request->input('telepon'),
            ]);

            if ($user->save()) {
                // Assign role to user
                $user->assignRole($request->input('role'));

                // Redirect to the index page with a success message
                return response()->json(['success' => true]);
            } else {
                // Redirect back with an error message if data saving fails
                return response()->json(['errors' => ['general' => 'User gagal disimpan']]);
            }
        } catch (\Exception $e) {
            // Log or handle the exception
            Log::error($e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
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
            'tmpt_lahir' => 'required',
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
