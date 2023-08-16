<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kas; // Assuming you have a "Kas" model for the "kas" table
use DataTables;
use Carbon\Carbon;
use Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
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
    
    // Helper function to format number as IDR currency
    public function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
    
    // ... (other methods)

    public function updateKasAwalForNextDay($date)
    {
        $currentDate = Carbon::parse($date);
        $nextDate = $currentDate->copy()->addDay();

        $kasForCurrentDate = Kas::where('date', $currentDate)
            ->where('user_id', auth()->id())
            ->first();

        if (!$kasForCurrentDate) {
            return "No 'kas' data found for the date: $date";
        }

        // Calculate the initial balance for the next day
        $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

        $nextDayRecord = Kas::where('date', $nextDate)
            ->where('user_id', auth()->id())
            ->first();

        if ($nextDayRecord) {
            $nextDayRecord->update([
                'kas_awal' => $kasAwalNextDay,
                'kas_akhir' => $kasAwalNextDay,
            ]);
        } else {
            $newKasRecord = Kas::create([
                'user_id' => auth()->id(),
                'kas_awal' => $kasAwalNextDay,
                'kas_masuk' => 0,
                'kas_keluar' => 0,
                'kas_akhir' => $kasAwalNextDay,
                'date' => $nextDate,
            ]);
        }

        return "Successfully updated 'kas_awal' for the next day ($nextDate)";
    }
}
