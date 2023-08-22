<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ledger::with('produk')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name; 
                })
                ->addColumn('produk', function ($row) {
                    return $row->produk->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('ledgers.edit', $row->id) . '" class="btn btn-sm btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.ledgers.index');
    }

    public function create()
    {
        $ledgers = Ledger::all();
        return view('pages.ledgers.create', compact('ledgers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:255|unique:ledger,kode',
            'name' => 'required|string|unique:ledger,name',
            'keterangan' => 'required|string|unique:ledger,keterangan',
        ], [
            'kode.required' => 'Kode ledger wajib diisi',
            'kode.unique' => 'Kode tersebut telah digunakan.',
            'name.required' => 'Nama ledger wajib diisi',
            'name.unique' => 'Nama tersebut telah digunakan.',
            'keterangan.required' => 'Keterangan ledger wajib diisi',
            'keterangan.unique' => 'Keterangan tersebut telah digunakan.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        Ledger::create([
            'kode' => $request->kode,
            'name' => $request->name,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dibuat'
        ]);
    }

    public function show($id)
    {
        $ledger = Ledger::findOrFail($id);

        return view('pages.ledgers.show', compact('ledger'));
    }

    public function edit($id)
    {
        $ledger = Ledger::findOrFail($id);

        return view('pages.ledgers.edit', compact('ledger'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:255|unique:ledger,kode,' . $id,
            'name' => 'required|string|unique:ledger,name,' . $id,
            'keterangan' => 'required|string|unique:ledger,keterangan,' . $id,
        ], [
            'kode.required' => 'Kode ledger wajib diisi',
            'kode.unique' => 'Kode tersebut telah digunakan.',
            'name.required' => 'Nama ledger wajib diisi',
            'name.unique' => 'Nama tersebut telah digunakan.',
            'keterangan.required' => 'Keterangan ledger wajib diisi',
            'keterangan.unique' => 'Keterangan tersebut telah digunakan.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ledger = Ledger::findOrFail($id);
        $newName = $request->input('name');
        
        if ($ledger->name !== $newName) {
            $uniqueCheck = Ledger::where('name', $newName)->where('id', '<>', $id)->count();
            if ($uniqueCheck > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama ledger sudah ada'
                ], 422);
            }
            $ledger->name = $newName;
        }

        $ledger->save();

        return response()->json([
            'success' => true,
            'message' => 'Ledger berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $ledger = Ledger::findOrFail($id);

        if ($ledger->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Ledger berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus ledger'
        ]);
    }
}
