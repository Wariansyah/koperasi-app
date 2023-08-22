<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:list-produk|create-produk|edit-produk|delete-produk', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-produk', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-produk', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-produk', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Produk::all(); // Menggunakan model Produk
            return Datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('produk.edit', $row->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.produk.index'); // Menggunakan view produk.index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.produk.create'); // Menggunakan view produk.create
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
            'kode' => 'required|string|max:255|unique:produk,kode',
            'ledger' => 'required|string|unique:produk,ledger',
            'keterangan' => 'required|string|unique:produk,keterangan',
        ]);

        $produkData = $request->all();
        $produk = Produk::create($produkData);

        return response()->json(['success' => true, 'message' => 'Produk created successfully.']);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::find($id);

        return view('pages.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $produk = Produk::find($id);

        return view('pages.produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode' => 'required',
            'ledger' => 'required',
            'keterangan' => 'required',
        ]);

        $produk = Produk::find($id);
        $produk->kode = $request->input('kode');
        $produk->ledger = $request->input('ledger');
        $produk->keterangan = $request->input('keterangan');
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete produk'
        ]);
    }
}
