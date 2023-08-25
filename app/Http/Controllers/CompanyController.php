<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:list-company|create-company|edit-company|delete-company', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:create-company', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-company', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-company', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    return asset('storage/' . $row->logo);
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

        // Set created_by and updated_by based on the authenticated user's name
        $companyData['created_by'] = Auth::user()->name;
        $companyData['updated_by'] = Auth::user()->name;

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
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|string',
            'telepon' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $companyData = $request->except('logo');

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logo', 'public');
            $companyData['logo'] = $logoPath;
        }

        $company = Company::findOrFail($id);
        $company->update($companyData);
        $company->updated_by = Auth::user()->name;

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
