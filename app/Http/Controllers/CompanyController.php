<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::select('id', 'name', 'email', 'logo', 'created_at')->paginate(10);
        // dd(env('APP_URL'));
        return Inertia::render('Company/Index', ['companies' => $companies, 'storage_url' => env('APP_URL'), 'toast_message' => session('toast_message')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Company/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        // dd($request);
        $imageName = time() . "." . $request->file('logo')->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('/', $request->logo, $imageName);


        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $imageName,
            'website' => $request->website
        ]);

        session()->flash('toast_message', 'Company stored successfully!');
        return to_route('company.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the company record from the database by ID
        $company = Company::findOrFail($id);

        // Return the edit page with the company data
        return Inertia::render('Company/Edit', [
            'company' => $company, // Pass the company data to the Inertia view
            'storage_url' => env('APP_URL'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        // dd($request);
        $imageName = NULL;
        if($request->logo) {
            $imageName = time() . "." . $request->file('logo')->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('/', $request->logo, $imageName);
        }

        Company::find($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $imageName ? $imageName : Company::find($id)->logo,
            'website' => $request->website,
        ]);

        session()->flash('toast_message', 'Company updated successfully!');
        // return to_route('company.index');
        return Inertia::location(route('company.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Company::find($id)->delete();

        session()->flash('toast_message', 'Company deleted successfully!');
        return to_route('company.index');
    }
}
