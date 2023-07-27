<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kas; // Assuming you have a "Kas" model for the "kas" table
use DataTables;
use DB;
use Carbon\Carbon;

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
                    // $button = '<a href="'.route('kas.edit', $row->id).'" class="btn btn-sm btn-warning">Edit</a>';
                    // $button .= ' <button class="btn btn-sm btn-danger" onclick="deleteItem('.$row->id.')">Delete</button>';
                    // return $button;

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
            'username' => 'required|string|max:255',
            'kas_awal' => 'required|numeric',
            'kas_masuk' => 'required|numeric',
            'kas_keluar' => 'required|numeric',
            'date' => 'required|date',
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
            'username' => 'required|string|max:255',
            'kas_awal' => 'required|numeric',
            'kas_masuk' => 'required|numeric',
            'kas_keluar' => 'required|numeric',
            'date' => 'required|date',
            // Add other validation rules as needed for 'note' field or any other fields.
        ]);

        $data = $request->all();
        $data['kas_akhir'] = $data['kas_awal'] + $data['kas_masuk'] - $data['kas_keluar'];

        DB::table('kas')->where('id', $id)->update($data);

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
        $data = DB::table('kas')->find($id);
        if (!$data) {
            return redirect()->route('kas.index')->with('error', 'Kas data not found!');
        }

        DB::table('kas')->where('id', $id)->delete();

        return redirect()->route('kas.index')->with('success', 'Data Kas has been deleted successfully!');
    }

    public function insertKasFromPreviousDay()
    {
        // Get the accumulated value from the previous day's "kas" record
        $previousDayKas = Kas::where('date', date('Y-m-d', strtotime('-1 day')))->first();
        $accumulatedValue = $previousDayKas ? $previousDayKas->current_value : 0;

        // Insert the new record with the accumulated value for the current day
        Kas::create([
            'date' => date('Y-m-d'),
            'current_value' => $accumulatedValue,
        ]);

        // Redirect back to the index page or perform any desired action
        return redirect()->route('kas.index')->with('success', 'New kas record inserted');
    }
}
