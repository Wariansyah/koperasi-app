<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::all();
            return DataTables::of($data)
                ->addColumn('logo', function ($row) {
                    return '<img src="' . asset('storage/' . $row->logo) . '" height="50" />';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('companies.edit', $row->id) . '" class="btn btn-primary">Edit</a>
                                  <button class="btn btn-danger delete-btn" data-id="' . $row->id . '">Delete</button>';
                    return $actionBtn;
                })
                ->rawColumns(['logo', 'action'])
                ->toJson();
        }
    
        return view('pages.companies.index');
    }
    


    public function create()
    {
        return view('pages.companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $companyData = $request->except('logo');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logo', 'public');
            $companyData['logo'] = $logoPath;
        }

        Company::create($companyData);

        return response()->json(['success' => true, 'message' => 'Company created successfully']);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['success' => true, 'message' => 'Company deleted successfully']);
    }
}
