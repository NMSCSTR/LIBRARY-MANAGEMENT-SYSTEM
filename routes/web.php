<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController, UserController,
    BookController, ReservationController, BorrowController,
    DonationController, ActivityLogController, CategoryController, PublisherController, SupplierController, BookCopyController
};

// Public
Route::get('/', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Authenticated
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ---------------- ADMIN ONLY ----------------
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    });

    // ---------------- LIBRARIAN + ADMIN ----------------
    Route::middleware(['role:admin,librarian'])->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('borrows', BorrowController::class);
        Route::resource('reservations', ReservationController::class);
        Route::resource('donations', DonationController::class)->only(['index','update']);
        Route::resource('categories', CategoryController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('book-copies', BookCopyController::class);
    });

    // ---------------- STUDENT + INSTRUCTOR + DONOR ----------------
    Route::middleware(['role:student,instructor,donor'])->group(function () {
        Route::post('/reserve/{book}', [ReservationController::class, 'reserve'])->name('reserve.book');
        Route::post('/donate', [DonationController::class, 'store'])->name('donations.store');
    });

});
