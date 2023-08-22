<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LedgerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:list-ledger|create-ledger|edit-ledger|delete-ledger', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-ledger', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-ledger', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-ledger', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ledger::all();
            return DataTables::of($data)
                ->addIndexColumn()
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
        return view('pages.ledgers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:255|unique:ledger',
            'name' => 'required|string|unique:ledger,name',
            'keterangan' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
    
        $formattedKode = preg_replace('/\D/', '', $request->input('kode'));
        $formattedKode = substr_replace($formattedKode, '.', 1, 0);
        $formattedKode = substr_replace($formattedKode, '.', 4, 0);
        $formattedKode = substr_replace($formattedKode, '.', 7, 0);
    
        $ledgerData = $request->all();
        $ledgerData['kode'] = $formattedKode;
    
        // Check if the kode already exists
        $existingLedger = Ledger::where('kode', $ledgerData['kode'])->first();
        if ($existingLedger) {
            return response()->json(['success' => false, 'errors' => ['kode' => ['Kode already exists.']]], 422);
        }
    
        // Create the ledger entry
        Ledger::create($ledgerData);
    
        return response()->json(['success' => true, 'message' => 'Ledger created successfully.']);
    }
    


    public function show($id)
    {
        $ledger = Ledger::find($id);

        return view('pages.ledgers.show', compact('ledger'));
    }

    public function edit($id)
    {
        $ledger = Ledger::find($id);

        return view('pages.ledgers.edit', compact('ledger'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:255|unique:ledger,kode,' . $id,
            'name' => 'required|string|unique:ledger,name,' . $id,
            'keterangan' => 'required|string|unique:ledger,keterangan,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $formattedKode = preg_replace('/\D/', '', $request->input('kode'));
        $formattedKode = substr_replace($formattedKode, '.', 1, 0);
        $formattedKode = substr_replace($formattedKode, '.', 4, 0);
        $formattedKode = substr_replace($formattedKode, '.', 7, 0);

        $ledgerData = $request->all();
        $ledgerData['kode'] = $formattedKode;

        $ledger = Ledger::find($id);
        $ledger->kode = $formattedKode;
        $ledger->name = $request->input('name');
        $ledger->keterangan = $request->input('keterangan');
        $ledger->save();

        return response()->json([
            'success' => true,
            'message' => 'Ledger successfully updated'
        ]);
    }

    public function destroy($id)
    {
        $ledger = Ledger::findOrFail($id);

        if ($ledger->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Ledger successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete ledger'
        ]);
    }
}
