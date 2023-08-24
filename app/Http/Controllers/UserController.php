<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{

    // function __construct()
    // {
    //     $this->middleware('permission:list-user|create-user|edit-user|delete-user', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    // }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = User::with('roles')->find(auth()->user()->id);
        $data = User::with('role')->get();

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
                            if ($role == 'Admin') {
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
                        $badge = '<span class="badge badge-info text-white">Belum Aktivasi</span>';
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
                ->addColumn('action', function ($row) {
                    $editUrl = route('users.edit', $row->id);
                    $deleteUrl = route('users.destroy', $row->id);

                    $editButton = '<a href="' . $editUrl . '" class="btn btn-sm btn-warning btn-icon btn-round"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $deleteButton = '<button onclick="deleteItem(this)" data-name="' . $row->name . '" data-id="' . $row->id . '" class="btn btn-sm btn-danger btn-icon btn-round delete-button"><i class="fas fa-trash"></i></button>';

                    return $editButton . '&nbsp;&nbsp;' . $deleteButton;
                })
                ->rawColumns(['role', 'status', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.users.index');
    }


    public function create()
    {
        $roles = Role::all(); // Fetch all roles from the database
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'no_induk' => 'required|string|unique:users',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telepon' => ['required', 'string', 'unique:users', 'regex:/^\d{10,12}$/'],
            'jenkel' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string',
            'role' => 'required|exists:roles,id',
            'limit_pinjaman' => 'required|numeric',
        ]);

        $userData = $request->all();
        $micro_id = explode(" ", microtime());
        $micro_id = $micro_id[1] . substr($micro_id[0], 2, 6);
        $userData['id'] = $micro_id;
        if ($request->input('status') === 'Belum Aktivasi') {
            $userData['status'] = '3';
        } elseif ($request->input('status') === 'Aktif') {
            $userData['status'] = '1';
        } else {
            $userData['status'] = '0';
        }
        $userData['password'] = Hash::make($request->input('password'));
        $userData['created_by'] = Auth::user()->name; 
        $userData['updated_by'] = Auth::user()->name; 
        $user = User::create($userData);
        $user->assignRole($request->input('role'));

        return response()->json(['success' => true, 'message' => 'User created successfully.']);
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
        $userRole = $user->roles->first();
        // dd($userRole);

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
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'no_induk' => 'required|string|unique:users,no_induk,' . $id,
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', // Password validation is optional during update
            'telepon' => ['required', 'string', 'unique:users,telepon,' . $id, 'regex:/^\d{10,12}$/'],
            'status' => 'required|string',
            'jenkel' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string',
            'role' => 'required|exists:roles,id',
            'limit_pinjaman' => 'required|numeric',
        ]);

        $userData = $request->except('password'); // Exclude password if it's not provided

        if ($request->has('password')) {
            $userData['password'] = Hash::make($request->input('password'));
        }

        $user->assignRole($request->input('role'));

        $user->update($userData);
        $user->updated_by = Auth::user()->name;

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
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
                'success' => true,
                'message' => 'User successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete user'
        ]);
    }
}
