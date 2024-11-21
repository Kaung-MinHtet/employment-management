<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('company')->paginate(10);

        return Inertia::render('User/Index', ['users' => $users, 'toast_message' => session('toast_message')]);
    }

    public function edit($id)
    {
        $user = User::find($id);

        // Fetch the company record from the database by ID
        $companies = Company::all();

        // Return the edit page with the company data
        return Inertia::render('User/Edit', [
            'user' => $user,
            'companies' => $companies, // Pass the company data to the Inertia view
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        session()->flash('toast_message', 'User updated successfully!');
        return to_route('user.index');
    }
}
