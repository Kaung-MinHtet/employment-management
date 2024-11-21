<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->role == "admin") {
            $employees = Employee::with('company')->paginate(10);
        } else {
            $employees = Employee::with('company')->where('company_id', auth()->user()->company_id ?? 0)->paginate(10);
        }
        return Inertia::render('Employee/Index', ['employees' => $employees, 'storage_url' => env('APP_URL'), 'toast_message' => session('toast_message')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->role == "admin") {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', auth()->user()->company_id ?? 0)->get();
        }
        return Inertia::render('Employee/Create', ['companies' => $companies]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStoreRequest $request)
    {
         $imageName = time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
         Storage::disk('public')->putFileAs('/', $request->profile_picture, $imageName);
 
 
         Employee::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $imageName,
            'phone' => $request->phone
         ]);
 
         session()->flash('toast_message', 'Employee stored successfully!');
         return to_route('employee.index');
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
         // Fetch the employee record from the database by ID
         $employee = Employee::findOrFail($id);
         if(auth()->user()->role == "admin") {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', auth()->user()->company_id ?? 0)->get();
        }

         // Return the edit page with the employee data
         return Inertia::render('Employee/Edit', [
             'employee' => $employee, // Pass the employee data to the Inertia view
             'companies' => $companies, // Pass the companies data to the Inertia view
             'storage_url' => env('APP_URL'),
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, string $id)
    {
        $imageName = NULL;
        if($request->profile_picture) {
            $imageName = time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('/', $request->profile_picture, $imageName);
        }

        Employee::find($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_picture' => $imageName ? $imageName : Employee::find($id)->profile_picture,
            'company_id' => $request->company_id,
        ]);

        session()->flash('toast_message', 'Company updated successfully!');
        return to_route('employee.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Employee::find($id)->delete();

        session()->flash('toast_message', 'Employee deleted successfully!');
        return to_route('employee.index');
    }
}
