<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kas; // Assuming you have a "Kas" model for the "kas" table
use DataTables;
use DB;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
