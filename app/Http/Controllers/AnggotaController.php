<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{



    function __construct()
    {
        $this->middleware('permission:list-user|create-user|edit-user|delete-user', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('anggota.edit', $row->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.anggota.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.anggota.create');
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
            'no_induk' => 'required|string|max:255|unique:anggota,no_induk',
            'nama' => 'required|string|max:255|unique:anggota,nama',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:255|unique:anggota,telepon',
            'jenkel' => 'required|string|max:255',
            'tnggl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string|max:255',
            'ibu_kandung' => 'required|string|max:255|unique:anggota,ibu_kandung',
        ]);
    
        $anggotaData = $request->all();
        $anggotaData['created_by'] = auth()->user()->id;
        $anggotaData['updated_by'] = auth()->user()->id;
        $anggota = Anggota::create($anggotaData);
    
        return response()->json(['success' => true, 'message' => 'Anggota created successfully.']);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anggota = Anggota::find($id);

        return view('pages.anggota.show', compact('anggota'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anggota = Anggota::find($id);

        return view('pages.anggota.edit', compact('anggota'));
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
            'no_induk' => 'required|string|max:255|unique:anggota,no_induk,' . $anggota->id,
            'nama' => 'required|string|max:255|unique:anggota,nama,' . $anggota->id,
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:255|unique:anggota,telepon,' . $anggota->id,
            'jenkel' => 'required|string|max:255',
            'tnggl_lahir' => 'required|date',
            'tmpt_lahir' => 'required|string|max:255',
            'ibu_kandung' => 'required|string|max:255|unique:anggota,ibu_kandung,' . $anggota->id,
        ]);
    

        $anggota = Anggota::find($id);
        $anggota->no_induk = $request->input('no_induk');
        $anggota->nama = $request->input('nama');
        $anggota->alamat = $request->input('alamat');
        $anggota->telepon = $request->input('telepon');
        $anggota->jenkel = $request->input('jenkel');
        $anggota->tnggl_lahir = $request->input('tnggl_lahir');
        $anggota->tmpt_lahir = $request->input('tmpt_lahir');
        $anggota->ibu_kandung = $request->input('ibu_kandung');
        $anggota->updated_by = auth()->user()->id; 
        $anggota->save();

        return response()->json([
            'success' => true,
            'message' => 'Anggota successfully updated'
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
        $anggota = Anggota::findOrFail($id);

        if ($anggota->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Anggota successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Anggota to delete produk'
        ]);
    }
}
