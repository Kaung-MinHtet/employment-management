<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController as APICompanyController;
use App\Http\Controllers\API\EmployeeController as APIEmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    if(auth()->user()->role == "admin") {
        $ttl_companies = Company::count();
        $ttl_employees = Employee::count();
        $recent_companies = Company::whereDate('created_at', now())->count();
        $recent_employees = Employee::whereDate('created_at', now())->count();
    } else {
        $ttl_companies = null;
        $ttl_employees = Employee::where('company_id', auth()->user()->company_id)->count();
        $recent_companies = null;
        $recent_employees = Employee::where('company_id', auth()->user()->company_id)->whereDate('created_at', now())->count();
    }
    
    return Inertia::render('Dashboard', [
        'ttl_companies' => $ttl_companies,
        'ttl_employees' => $ttl_employees,
        'recent_companies' => $recent_companies,
        'recent_employees' => $recent_employees,
        'user' => auth()->user()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // company
    Route::resource('/company', CompanyController::class);

    // employee
    Route::resource('/employee', EmployeeController::class);

    // user
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
});

require __DIR__.'/auth.php';
