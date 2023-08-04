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
        // Fetch the 'kas' data for the given date
        $kasForCurrentDate = Kas::where('date', $date)->first();

        if (!$kasForCurrentDate) {
            // No data found for the given date, handle the case accordingly
            // For example, you might want to throw an error or return a message
            return "No 'kas' data found for the date: $date";
        }

        // Calculate the 'kas_awal' for the next day (adding 'kas_akhir' to 'kas_masuk' and subtracting 'kas_keluar')
        $kasAwalNextDay = $kasForCurrentDate->kas_akhir + $kasForCurrentDate->kas_masuk - $kasForCurrentDate->kas_keluar;

        // Insert a new row for the next day with the calculated 'kas_awal' value
        $nextDate = date('Y-m-d', strtotime($date . ' +1 day'));
        $newKasRecord = new Kas([
            'kas_awal' => $kasAwalNextDay,
            'kas_masuk' => 0, // Assuming you start each day with 0 'kas_masuk'
            'kas_keluar' => 0, // Assuming you start each day with 0 'kas_keluar'
            'kas_akhir' => $kasAwalNextDay, // 'kas_akhir' will be the same as 'kas_awal' at the beginning of the day
            'date' => $nextDate,
            'note' => 'Automatically generated for the next day',
        ]);

        $newKasRecord->save();

        return "Successfully updated 'kas_awal' for the next day ($nextDate)";
    }
}
