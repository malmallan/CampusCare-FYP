
<?php

// FAIL: routes/web.php

use App\Http\Controllers\AdminComplaintController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TechnicianController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// ---------- TAMU SAHAJA ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// ---------- PERLU LOG MASUK ----------
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        if (auth()->user()->isTechnician()) {
            return redirect()->route('jobs.index');
        }

        return view('dashboard');
    })->name('dashboard');

    // ---------- USER MANUAL ----------
    // Semua user yang login boleh akses.
    Route::get('/user-manual', function () {
        return view('manual.index');
    })->name('manual.index');

    // ---------- PELAJAR ----------
    Route::middleware('role:student')->group(function () {
        Route::get('/complaints', [ComplaintController::class, 'index'])
            ->name('complaints.index');

        Route::get('/complaints/create', [ComplaintController::class, 'create'])
            ->name('complaints.create');

        Route::post('/complaints', [ComplaintController::class, 'store'])
            ->name('complaints.store');
    });

    // ---------- ADMIN ----------
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/complaints', [AdminComplaintController::class, 'index'])
                ->name('complaints.index');

            Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])
                ->name('complaints.show');

            Route::patch('/complaints/{complaint}/assign', [AdminComplaintController::class, 'assign'])
                ->name('complaints.assign');
        });

    // ---------- TEKNISYEN ----------
    Route::middleware('role:technician')->group(function () {

        Route::get('/jobs', [TechnicianController::class, 'index'])
            ->name('jobs.index');

        Route::patch('/jobs/{complaint}/start', [TechnicianController::class, 'start'])
            ->name('jobs.start');

        Route::patch('/jobs/{complaint}/complete', [TechnicianController::class, 'complete'])
            ->name('jobs.complete');
    });
});
