<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kas; // Assuming you have a "Kas" model for the "kas" table
use DataTables;
use Carbon\Carbon;
use Response;
use Illuminate\Http\JsonResponse;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kas::all();
    
            return DataTables::of($data)
                ->addColumn('user_id', function ($row) {
                    return $row->user->name; // Assuming you have a User relationship in your Kas model
                })
                ->editColumn('kas_awal', function ($row) {
                    return formatRupiah($row->kas_awal);
                })
                ->editColumn('kas_masuk', function ($row) {
                    return formatRupiah($row->kas_masuk);
                })
                ->editColumn('kas_keluar', function ($row) {
                    return formatRupiah($row->kas_keluar);
                })
                ->editColumn('kas_akhir', function ($row) {
                    return formatRupiah($row->kas_akhir);
                })
                
                ->rawColumns(['user_id', 'date']) // Add rawColumns for HTML content
                ->make(true);
        }
    
        return view('kas.index');
    }
    
    


    // ... (other methods)

    public function updateKasAwalForNextDay($date)
    {
        $currentDate = Carbon::parse($date);
        $nextDate = $currentDate->copy()->addDay();

        $kasForCurrentDate = Kas::where('date', $currentDate)
            ->where('user_id', auth()->id()) // Add the user_id filter
            ->first();

        if (!$kasForCurrentDate) {
            return "No 'kas' data found for the date: $date";
        }

        $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

        $nextDayRecord = Kas::where('date', $nextDate)
            ->where('user_id', auth()->id()) // Add the user_id filter
            ->first();

        if ($nextDayRecord) {
            $nextDayRecord->update([
                'kas_awal' => $kasAwalNextDay,
                'kas_akhir' => $kasAwalNextDay,
            ]);
        } else {
            $newKasRecord = Kas::create([
                'user_id' => auth()->id(), // Set the user_id
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
