<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pinjaman::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pinjaman.edit', $row->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.pinjaman.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.pinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    use Illuminate\Support\Facades\Auth;
    use App\Models\Pinjaman;
    use Illuminate\Http\Request;
    
    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric',
            'tgl_pinjam' => 'required|date',
            'keuntungan' => 'required|numeric',
            'rate_keuntungan' => 'required|numeric',
            'jangka_waktu' => 'required|integer',
            'tgl_jatuh_tempo' => 'required|date',
            'sisa_pinjaman' => 'required|numeric',
            'sisa_keuntungan' => 'required|numeric',
            'nominal_tunggakan' => 'required|numeric',
            'kali_tunggakan' => 'required|integer',
            'tgl_tunggakan' => 'required|date',
            'penggunaan' => 'required|string',
            'tgl_lunas' => 'nullable|date',
            'otorisasi_by' => 'required|string', 
        ]);
    
        $pinjamanData = $request->all();
        $pinjamanData['otorisasi_by'] = auth()->user()->id;
        $pinjamanData['created_by'] = auth()->user()->id;
        $pinjamanData['updated_by'] = auth()->user()->id;
        $pinjaman = Pinjaman::create($pinjamanData);
    
        return response()->json(['success' => true, 'message' => 'Data pinjaman berhasil disimpan.']);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pinjaman  $pinjaman
     * @return \Illuminate\Http\Response
     */
    public function show(Pinjaman $pinjaman)
    {
        $pinjaman = Pinjaman::find($pinjaman);

        return view('pages.pinjaman.show', compact('pinjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pinjaman  $pinjaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Pinjaman $pinjaman)
    {
        $pinjaman = Pinjaman::find($pinjaman);

        return view('pages.pinjaman.edit', compact('pinjaman'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pinjaman  $pinjaman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'nominal' => 'required|numeric',
            'tgl_pinjam' => 'required|date',
            'keuntungan' => 'required|numeric',
            'rate_keuntungan' => 'required|numeric',
            'jangka_waktu' => 'required|integer',
            'tgl_jatuh_tempo' => 'required|date',
            'sisa_pinjaman' => 'required|numeric',
            'sisa_keuntungan' => 'required|numeric',
            'nominal_tunggakan' => 'required|numeric',
            'kali_tunggakan' => 'required|integer',
            'tgl_tunggakan' => 'required|date',
            'penggunaan' => 'required|string',
            'tgl_lunas' => 'nullable|date',
            'otorisasi_by' => 'required|string', 
        ]);
    
    
        $pinjaman->user_id = $request->input('user_id');
        $pinjaman->nominal = $request->input('nominal');
        $pinjaman->tgl_pinjam = $request->input('tgl_pinjam');
        $pinjaman->keuntungan = $request->input('keuntungan');
        $pinjaman->rate_keuntungan = $request->input('rate_keuntungan');
        $pinjaman->jangka_waktu = $request->input('jangka_waktu');
        $pinjaman->tgl_jatuh_tempo = $request->input('tgl_jatuh_tempo');
        $pinjaman->sisa_pinjaman = $request->input('sisa_pinjaman');
        $pinjaman->sisa_keuntungan = $request->input('sisa_keuntungan');
        $pinjaman->nominal_tunggakan = $request->input('nominal_tunggakan');
        $pinjaman->kali_tunggakan = $request->input('kali_tunggakan');
        $pinjaman->tgl_tunggakan = $request->input('tgl_tunggakan');
        $pinjaman->penggunaan = $request->input('penggunaan');
        $pinjaman->tgl_lunas = $request->input('tgl_lunas');
        $pinjaman->otorisasi_by = $request->input('otorisasi_by');
        $pinjaman->updated_by = auth()->user()->id;
        $pinjaman->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Pinjaman successfully updated'
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pinjaman  $pinjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pinjaman $pinjaman)
    {
        $pinjaman = Pinjaman::findOrFail($pinjaman);

        if ($pinjaman->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Pinjaman successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pinjaman to delete produk'
        ]);
    }
}
