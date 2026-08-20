<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/directors-desk', [PublicController::class, 'directorsDesk'])->name('directors-desk');
Route::post('/contact/submit', [PublicController::class, 'submitContact'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes (Scratch)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Employee Auth Routes (Scratch)
|--------------------------------------------------------------------------
*/
Route::get('/login', [EmployeeAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [EmployeeAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [EmployeeAuthController::class, 'logout'])->name('employee.logout');

// Forced Password Change (For first-login employees)
Route::middleware(['auth.employee'])->group(function () {
    Route::get('/password/change', [EmployeeAuthController::class, 'showPasswordChange'])->name('employee.password.change');
    Route::post('/password/change', [EmployeeAuthController::class, 'updatePassword'])->name('employee.password.change.update');
});

/*
|--------------------------------------------------------------------------
| Staff Auth Routes (Scratch)
|--------------------------------------------------------------------------
*/
Route::get('/staff/login', [StaffAuthController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

// Forced Password Change (For first-login staff)
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/staff/password/change', [StaffAuthController::class, 'showPasswordChange'])->name('staff.password.change');
    Route::post('/staff/password/change', [StaffAuthController::class, 'updatePassword'])->name('staff.password.change.update');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.admin', 'password.change.staff'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin/Staff Profile & Password Security
    Route::get('/profile', [AdminDashboardController::class, 'profileShow'])->name('profile');
    Route::put('/profile/update', [AdminDashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/profile/password', [AdminDashboardController::class, 'passwordUpdate'])->name('profile.password');

    // Employees CRUD
    Route::middleware(['can:manage_employees'])->group(function () {
        Route::get('/employees', [AdminDashboardController::class, 'employeesIndex'])->name('employees.index');
        Route::get('/employees/create', [AdminDashboardController::class, 'employeesCreate'])->name('employees.create');
        Route::post('/employees/store', [AdminDashboardController::class, 'employeesStore'])->name('employees.store');
        Route::get('/employees/download-template', [AdminDashboardController::class, 'downloadEmployeeTemplate'])->name('employees.download-template');
        Route::get('/employees/export', [AdminDashboardController::class, 'employeesExport'])->name('employees.export');
        Route::get('/employees/{employee}/edit', [AdminDashboardController::class, 'employeesEdit'])->name('employees.edit');
        Route::put('/employees/{employee}/update', [AdminDashboardController::class, 'employeesUpdate'])->name('employees.update');
        Route::delete('/employees/{employee}/destroy', [AdminDashboardController::class, 'employeesDestroy'])->name('employees.destroy');
        Route::post('/employees/import', [AdminDashboardController::class, 'employeesImport'])->name('employees.import');
        Route::post('/employees/{employee}/login-as', [AdminDashboardController::class, 'loginAsEmployee'])->name('employees.login-as');
    });

    // Companies CRUD
    Route::middleware(['can:manage_companies'])->group(function () {
        Route::get('/companies', [AdminDashboardController::class, 'companiesIndex'])->name('companies.index');
        Route::get('/companies/create', [AdminDashboardController::class, 'companiesCreate'])->name('companies.create');
        Route::post('/companies/store', [AdminDashboardController::class, 'companiesStore'])->name('companies.store');
        Route::get('/companies/{company}/edit', [AdminDashboardController::class, 'companiesEdit'])->name('companies.edit');
        Route::put('/companies/{company}/update', [AdminDashboardController::class, 'companiesUpdate'])->name('companies.update');
        Route::delete('/companies/{company}/destroy', [AdminDashboardController::class, 'companiesDestroy'])->name('companies.destroy');
    });

    // Departments CRUD
    Route::middleware(['can:manage_departments'])->group(function () {
        Route::get('/departments', [AdminDashboardController::class, 'departmentsIndex'])->name('departments.index');
        Route::get('/departments/create', [AdminDashboardController::class, 'departmentsCreate'])->name('departments.create');
        Route::post('/departments/store', [AdminDashboardController::class, 'departmentsStore'])->name('departments.store');
        Route::get('/departments/{department}/edit', [AdminDashboardController::class, 'departmentsEdit'])->name('departments.edit');
        Route::put('/departments/{department}/update', [AdminDashboardController::class, 'departmentsUpdate'])->name('departments.update');
        Route::delete('/departments/{department}/destroy', [AdminDashboardController::class, 'departmentsDestroy'])->name('departments.destroy');
    });

    // Designations CRUD
    Route::middleware(['can:manage_designations'])->group(function () {
        Route::get('/designations', [AdminDashboardController::class, 'designationsIndex'])->name('designations.index');
        Route::get('/designations/create', [AdminDashboardController::class, 'designationsCreate'])->name('designations.create');
        Route::post('/designations/store', [AdminDashboardController::class, 'designationsStore'])->name('designations.store');
        Route::get('/designations/{designation}/edit', [AdminDashboardController::class, 'designationsEdit'])->name('designations.edit');
        Route::put('/designations/{designation}/update', [AdminDashboardController::class, 'designationsUpdate'])->name('designations.update');
        Route::delete('/designations/{designation}/destroy', [AdminDashboardController::class, 'designationsDestroy'])->name('designations.destroy');
    });

    // Offer Letter Generation
    Route::middleware(['can:manage_offer_letters'])->group(function () {
        Route::get('/offer-letters/generate', [AdminDashboardController::class, 'showGenerateOfferLetter'])->name('offer-letters.generate');
        Route::post('/offer-letters/generate', [AdminDashboardController::class, 'generateOfferLetter'])->name('offer-letters.generate.submit');
        Route::post('/offer-letters/bulk-generate-selected', [AdminDashboardController::class, 'bulkGenerateSelected'])->name('offer-letters.bulk-generate-selected');
        Route::post('/offer-letters/bulk', [AdminDashboardController::class, 'generateOfferLettersBulk'])->name('offer-letters.bulk');
    });

    // Payslips Generation
    Route::middleware(['can:manage_payslips'])->group(function () {
        Route::get('/payslips/generate', [AdminDashboardController::class, 'showGeneratePayslip'])->name('payslips.generate');
        Route::post('/payslips/generate', [AdminDashboardController::class, 'generatePayslip'])->name('payslips.generate.submit');
        Route::post('/payslips/download-prefilled', [AdminDashboardController::class, 'downloadPrefilledPayslipTemplate'])->name('payslips.download-prefilled');
        Route::post('/payslips/bulk', [AdminDashboardController::class, 'generatePayslipsBulk'])->name('payslips.bulk');
        Route::get('/payslips/template', [AdminDashboardController::class, 'downloadPayslipTemplate'])->name('payslips.template');
    });

    // Bulletins & Notices
    Route::middleware(['can:manage_bulletins'])->group(function () {
        Route::get('/bulletins', [AdminDashboardController::class, 'bulletinsIndex'])->name('bulletins.index');
        Route::post('/bulletins/store', [AdminDashboardController::class, 'bulletinsStore'])->name('bulletins.store');
        Route::put('/bulletins/{bulletin}/update', [AdminDashboardController::class, 'bulletinsUpdate'])->name('bulletins.update');
        Route::delete('/bulletins/{bulletin}/destroy', [AdminDashboardController::class, 'bulletinsDestroy'])->name('bulletins.destroy');
    });

    // CMS & Inquiries Inbox
    Route::middleware(['can:manage_inquiries'])->group(function () {
        Route::get('/inquiries', [AdminDashboardController::class, 'inquiriesIndex'])->name('inquiries.index');
        Route::post('/inquiries/{inquiry}/reply', [AdminDashboardController::class, 'inquiriesReply'])->name('inquiries.reply');
    });
    
    Route::middleware(['can:manage_cms'])->group(function () {
        Route::get('/cms', [AdminDashboardController::class, 'cmsIndex'])->name('cms.index');
        Route::post('/cms/update', [AdminDashboardController::class, 'cmsUpdate'])->name('cms.update');
    });

    // Staff Management CRUD (Only Admins can access staff CRUD. Since admins bypass all gate checks, let's use a specific gate, e.g. manage_staff, which is not assigned to any staff)
    Route::middleware(['can:manage_staff'])->group(function () {
        Route::get('/staff', [StaffManagementController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffManagementController::class, 'create'])->name('staff.create');
        Route::post('/staff/store', [StaffManagementController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [StaffManagementController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}/update', [StaffManagementController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}/destroy', [StaffManagementController::class, 'destroy'])->name('staff.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Protected Employee Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.employee', 'password.change'])->prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [EmployeePortalController::class, 'index'])->name('dashboard');

    // Profile management
    Route::post('/profile/update', [EmployeePortalController::class, 'requestProfileUpdate'])->name('profile.update');

    // Documents Center & Bulletin
    Route::get('/documents', [EmployeePortalController::class, 'documents'])->name('documents');
    Route::get('/documents/download/offer-letter/{offerLetter}', [EmployeePortalController::class, 'downloadOfferLetter'])->name('download.offer-letter');
    Route::get('/documents/download/payslip/{payslip}', [EmployeePortalController::class, 'downloadPayslip'])->name('download.payslip');
    Route::get('/bulletins', [EmployeePortalController::class, 'bulletins'])->name('bulletins');
});
