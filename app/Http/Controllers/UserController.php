<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $roles = Role::all();
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'tgl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string|max:255',
            'no_induk' => 'required|string|max:50',
            'jenkel' => 'required|in:male,female',
            'role_id' => 'required|exists:roles,id',
            // Add other fields and validation rules as needed
        ]);

        // Save the user data here
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'alamat' => $request->input('alamat'),
            'telepon' => $request->input('telepon'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'tmpt_lahir' => $request->input('tmpt_lahir'),
            'no_induk' => $request->input('no_induk'),
            'jenkel' => $request->input('jenkel'),
            'role_id' => $request->input('role_id'), // Use 'role_id' here
        ]);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRole = $user->roles->pluck('id')->all();

        return view('pages.users.edit', compact('user', 'roles', 'userRole'));
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
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,' . $id,
            'address' => 'required|string',
            'phone' => 'required|string',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string',
            'no_induk' => 'required|string',
            'jenis_kelamin' => 'required|in:male,female',
            'role' => 'required|exists:roles,id'
        ]);

        $data = $request->all();
        $user = User::findOrFail($id);
        $user->update($data);
        $user->syncRoles([$request->input('role')]);

        if ($user) {
            return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
        }

        return response()->json([
            'code' => 400,
            'message' => 'User gagal diupdate'
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
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'User berhasil dihapus'
            ]);
        }

        return response()->json([
            'code' => 400,
            'message' => 'User gagal dihapus'
        ]);
    }
}
