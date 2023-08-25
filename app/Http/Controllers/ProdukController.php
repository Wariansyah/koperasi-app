<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:list-produk|create-produk|edit-produk|delete-produk', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:create-produk', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-produk', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-produk', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Produk::with('ledger')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('produk.edit', $row->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ledgers = Ledger::all();
        return view('pages.produk.create', compact('ledgers'));
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
            'ledger' => 'required|string|exists:ledger,kode',
            'keterangan' => 'required|string|unique:produk,keterangan',
        ]);

        $produkData = $request->all();

        // Retrieve the Ledger model based on the provided kode value
        $ledger = Ledger::where('kode', $request->input('ledger'))->first();

        // Create the produk entry and associate it with the Ledger model
        $produk = new Produk();
        $produk->fill($produkData);
        $produk->kode = $request->input('kode');
        $produk->ledger()->associate($ledger);
        $produk->save();

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
        $produk = Produk::with('ledger')->find($id);
        return view('pages.produk.show', compact('produk'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produk = Produk::with('ledger')->find($id);
        $ledgers = Ledger::all();
        return view('pages.produk.edit', compact('produk', 'ledgers'));
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
        $request->validate([
            'kode' => 'required|string|max:255|unique:produk,kode,' . $id,
            'ledger' => 'required|string|exists:ledger,kode',
            'keterangan' => 'required|string|unique:produk,keterangan,' . $id,
        ]);
        $produkData = $request->all();

        // Retrieve the Ledger model based on the provided kode value
        $ledger = Ledger::where('kode', $request->input('ledger'))->first();

        // Update the produk entry and associate it with the Ledger model
        $produk = Produk::find($id);
        $produk->fill($produkData);
        $produk->ledger()->associate($ledger);
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
