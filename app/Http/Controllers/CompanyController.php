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
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    return '<img src="' . asset('storage/' . $row->logo) . '" height="50" />';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('companies.edit', $row->id) . '" class="btn btn-warning"><i class="fas fa-pen-square fa-circle mt-2"></i></a>
                                  <button class="btn btn-danger delete-btn" data-id="' . $row->id . '" onclick="deleteItem(this)"><i class="fas fa-trash"></i></button>';
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
            'nama' => 'required|string|unique:company,nama',
            'alamat' => 'required|string',
            'email' => 'required|string|unique:company,email',
            'telepon' => 'required|string|unique:company,telepon',
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

    public function show($id)
    {
        $company = Company::find($id);

        return view('pages.companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::find($id);

        return view('pages.companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|unique:company,nama,' . $id,
            'alamat' => 'required|string',
            'email' => 'required|string|unique:company,email,' . $id,
            'telepon' => 'required|string|unique:company,telepon,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $companyData = $request->except('logo');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logo', 'public');
            $companyData['logo'] = $logoPath;
        }

        $company = Company::findOrFail($id);
        $company->update($companyData);

        return response()->json(['success' => true, 'message' => 'Company updated successfully']);
    }



    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        if ($company->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Company successfully deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Company to delete produk'
        ]);
    }
}
