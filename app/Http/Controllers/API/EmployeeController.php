<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    // List all employees
    public function index()
    {
        return response()->json(Employee::all(), 200);
    }

    // Store a new employee
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'phone' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName = time() . $request->file('profile_picture')->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('/', $request->profile_picture, $imageName);

        $employee = Employee::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $imageName,
            'phone' => $request->phone
        ]);

        return response()->json($employee, 201);
    }

    // Show a single employee
    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json($employee, 200);
    }

    // Update a employee
    public function update(Request $request, $id)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $id,
            'profile_picture' => 'nullable|image|max:2048',
            'phone' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName = NULL;
        if($request->profile_picture) {
            $imageName = time() . $request->file('profile_picture')->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('/', $request->profile_picture, $imageName);
        }

        Employee::find($id)->update([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $imageName ? $imageName : Employee::find($id)->profile_picture,
            'phone' => $request->phone,
        ]);

        $employee = Employee::findOrFail($id);
        // return dd($employee);
        return response()->json($employee, 200);
    }

    // Delete a employee
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(['message' => 'Successfully deleted'], 204);
    }
}
