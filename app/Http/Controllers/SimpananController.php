<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;

class SimpananController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:list-simpanan|create-simpanan|edit-simpanan|delete-simpanan', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-simpanan', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-simpanan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-simpanan', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $data = Kas::where('user_id', $user->id)->orderBy('date','DESC')->get();
        // dd($data->toArray());
        if ($request->ajax()) {
    
            return DataTables()::of($data)
                ->addColumn('user_id', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('kas_awal', function ($row) {
                    return $this->formatRupiah($row->kas_awal);
                })
                ->editColumn('kas_masuk', function ($row) {
                    return $this->formatRupiah($row->kas_masuk);
                })
                ->editColumn('kas_keluar', function ($row) {
                    return $this->formatRupiah($row->kas_keluar);
                })
                ->editColumn('kas_akhir', function ($row) {
                    return $this->formatRupiah($row->kas_akhir);
                })
                ->addIndexColumn()
                ->rawColumns(['user_id'])
                ->make(true);
        }
    
        return view('pages.kas.index');
    }
   
}
