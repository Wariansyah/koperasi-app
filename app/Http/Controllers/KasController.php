<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kas; // Assuming you have a "Kas" model for the "kas" table
use DataTables;
use DB;
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
            $data = DB::table('kas')->get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    // Add any action buttons you want for each row
                    // For example:
                    $button = '<a href="' . route('kas.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>';
                    $button .= ' <button type="button" class="btn btn-sm btn-danger" data-id="' . $row->id . '" onclick="deleteItem(this)">Delete</button>';
                    return $button;

                    // Replace the example above with your desired action buttons.
                })
                ->editColumn('date', function ($row) {
                    // You can format the date if needed
                    return Carbon::parse($row->date)->format('Y-m-d');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kas.create');
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
            'kas_awal' => 'required|numeric',
            'kas_masuk' => 'required|numeric',
            'kas_keluar' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'required|string|nullable',
            // Add other validation rules as needed for 'note' field or any other fields.
        ]);

        $data = $request->except('_token'); // Remove the _token field from the $data array
        $data['kas_akhir'] = $data['kas_awal'] + $data['kas_masuk'] - $data['kas_keluar'];

        DB::table('kas')->insert($data);

        return redirect()->route('kas.index')->with('success', 'Data Kas has been created successfully!');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table('kas')->find($id);
        if (!$data) {
            return redirect()->route('kas.index')->with('error', 'Kas data not found!');
        }

        return view('kas.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DB::table('kas')->find($id);
        if (!$data) {
            return redirect()->route('kas.index')->with('error', 'Kas data not found!');
        }

        return view('kas.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {
        $request->validate([
            'kas_awal' => 'required|numeric',
            'kas_masuk' => 'required|numeric',
            'kas_keluar' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'required|string|nullable',
            // Add other validation rules as needed for 'note' field or any other fields.
        ]);

        $data = $request->all();
        $data['kas_akhir'] = $data['kas_awal'] + $data['kas_masuk'] - $data['kas_keluar'];

        $kas = Kas::find($id);
        if (!$kas) {
            return new JsonResponse(['success' => false, 'message' => 'Kas data not found!'], 404);
        }

        $kas->update($data);

        if ($request->ajax()) {
            return new JsonResponse(['success' => true, 'message' => 'Data Kas has been updated successfully!']);
        }

        return redirect()->route('kas.index')->with('success', 'Data Kas has been updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kas = Kas::find($id);
        if (!$kas) {
            return response()->json([
                'code' => 404,
                'message' => 'Kas data not found'
            ]);
        }

        if ($kas->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Kas data has been deleted successfully'
            ]);
        }

        return response()->json([
            'code' => 500,
            'message' => 'Failed to delete Kas data'
        ]);
    }

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
    public function anyOtherMethod()
    {
        $dateToUpdate = '2023-07-28'; // Change this to any date you want to update
        $result = $this->updateKasAwalForNextDay($dateToUpdate);
        echo $result; // Output: Successfully updated 'kas_awal' for the next day (2023-07-29)
    }

}
