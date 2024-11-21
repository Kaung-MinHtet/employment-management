<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    // List all companies
    public function index()
    {
        return response()->json(Company::all(), 200);
    }

    // Store a new company
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'website' => 'nullable|url',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName = time() . $request->file('logo')->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('/', $request->logo, $imageName);

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $imageName,
            'website' => $request->website
        ]);

        return response()->json($company, 201);
    }

    // Show a single company
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return response()->json($company, 200);
    }

    // Update a company
    public function update(Request $request, $id)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:companies,email,' . $id,
            'logo' => 'nullable|image|max:2048',
            'website' => 'nullable|url',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName = NULL;
        if($request->logo) {
            $imageName = time() . $request->file('logo')->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('/', $request->logo, $imageName);
        }

        Company::find($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'logo' => $imageName ? $imageName : Company::find($id)->logo,
            'website' => $request->website,
        ]);

        $company = Company::findOrFail($id);
        // return dd($company);
        return response()->json($company, 200);
    }

    // Delete a company
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['message' => 'Successfully deleted'], 204);
    }
}
