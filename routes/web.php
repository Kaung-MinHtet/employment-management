<?php

use App\Http\Controllers\API\AuthController;
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

        $companies = Company::get()->pluck('name')->toArray();

        $employee_per_company = [];
        foreach (Company::all() as $company) {
            $employee_count = Employee::where('company_id', $company->id)->count();

            array_push($employee_per_company, $employee_count);
        }
    } else {
        $ttl_companies = null;
        $ttl_employees = Employee::where('company_id', auth()->user()->company_id ?? 0)->count();
        $recent_companies = null;
        $recent_employees = Employee::where('company_id', auth()->user()->company_id ?? 0)->whereDate('created_at', now())->count();
        
        $companies = Company::where('id', auth()->user()->company_id ?? 0)->get()->pluck('name')->toArray();

        $employee_per_company = [];
        foreach (Company::where('id', auth()->user()->company_id ?? 0)->get() as $company) {
            $employee_count = Employee::where('company_id', $company->id)->count();

            array_push($employee_per_company, $employee_count);
        }
    }

    return Inertia::render('Dashboard', [
        'ttl_companies' => $ttl_companies,
        'ttl_employees' => $ttl_employees,
        'recent_companies' => $recent_companies,
        'recent_employees' => $recent_employees,
        'user' => auth()->user(),
        'companies' => $companies,
        'employee_per_company' => $employee_per_company
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // company
    // Route::resource('/company', CompanyController::class);
    Route::middleware('can:admin-access')->group(function() {
        Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
        Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
        Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/company/{id}/edit', [CompanyController::class, 'edit'])->name('company.edit');
        Route::post('/company/{id}/update', [CompanyController::class, 'update'])->name('company.update');
        Route::delete('/company/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
    });

    // employee
    // Route::resource('/employee', EmployeeController::class);
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::post('/employee/{id}/update', [EmployeeController::class, 'update'])->middleware('can:manage-employee')->name('employee.update');
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->middleware('can:manage-employee')->name('employee.destroy');

    // user
    Route::middleware('can:admin-access')->group(function() {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    });
    
});

require __DIR__.'/auth.php';
