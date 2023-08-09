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
                ->addColumn('date', function ($row) {
                    return $row->date->format('Y-m-d');
                })
                ->make(true);
        }

        return view('kas.index');
    }


    // ... (other methods)

    public function updateKasAwalForNextDay($date)
    {
        $currentDate = Carbon::parse($date);
        $nextDate = $currentDate->copy()->addDay();

        $kasForCurrentDate = Kas::where('date', $currentDate)->first();

        if (!$kasForCurrentDate) {
            return "No 'kas' data found for the date: $date";
        }

        $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

        $nextDayRecord = Kas::where('date', $nextDate)->first();

        if ($nextDayRecord) {
            $nextDayRecord->update([
                'kas_awal' => $kasAwalNextDay,
                'kas_akhir' => $kasAwalNextDay,
            ]);
        } else {
            $newKasRecord = Kas::create([
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
