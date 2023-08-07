<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use DataTables;
use Auth;
use App\Models\User;
use Hash;
use Illuminate\Support\Arr;
use DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

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
        $auth = auth()->user()->with('permissions')->first();
        $data = User::all();

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
        return view('pages.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'no_induk' => 'required|string|unique:users',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'telepon' => 'required|string|unique:users',
            'status' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string',
            'limit_pinjaman' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = new User([
            'name' => $request->input('name'),
            'no_induk' => $request->input('no_induk'),
            'alamat' => $request->input('alamat'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'telepon' => $request->input('telepon'),
            'status' => $request->input('status'),
            'jenkel' => $request->input('jenis_kelamin'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'tmpt_lahir' => $request->input('tempat_lahir'),
            'limit_pinjaman' => $request->input('limit_pinjaman'),
        ]);

        $user->save();

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'required|string',
            'telepon' => 'required|string',
            'status' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string',
            'no_induk' => 'required|string',
            'jenkel' => 'required|string',
            'limit_pinjaman' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->alamat = $request->input('alamat');
        $user->telepon = $request->input('telepon');
        $user->status = $request->input('status');
        $user->tgl_lahir = $request->input('tgl_lahir');
        $user->tmpt_lahir = $request->input('tmpt_lahir');
        $user->no_induk = $request->input('no_induk');
        $user->jenkel = $request->input('jenkel');
        $user->limit_pinjaman = $request->input('limit_pinjaman');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User successfully updated'
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
