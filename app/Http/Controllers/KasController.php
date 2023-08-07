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
            $data = Kas::all(); // Use the Kas model to fetch data
            return DataTables::of($data)
                ->editColumn('date', function ($row) {
                    // You can format the date if needed
                    return Carbon::parse($row->date)->format('Y-m-d');
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
                'note' => 'Automatically updated kas_awal',
            ]);
        } else {
            $newKasRecord = Kas::create([
                'kas_awal' => $kasAwalNextDay,
                'kas_masuk' => 0,
                'kas_keluar' => 0,
                'kas_akhir' => $kasAwalNextDay,
                'date' => $nextDate,
                'note' => 'Automatically generated for the next day',
            ]);
        }

        return "Successfully updated 'kas_awal' for the next day ($nextDate)";
    }
}
