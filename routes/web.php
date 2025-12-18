<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\BorrowerDashboardController;
use App\Http\Controllers\BorrowerProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('welcome');

/*
|--------------------------------------------------------------------------
| Guest-only Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('users.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('users.login.submit');

    // Registration
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('users.logout');

    /*
    |--------------------------------------------------------------------------
    | Admin & Librarian
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('role:admin,librarian')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('authors', AuthorController::class);
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('publishers', PublisherController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('book-copies', BookCopyController::class);
        Route::resource('borrows', BorrowController::class);
        Route::put('/borrows/{id}/return', [BorrowController::class, 'return'])
            ->name('borrows.return');

        Route::resource('activity-logs', ActivityLogController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin-only
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Borrower
    |--------------------------------------------------------------------------
    */
    Route::get('/borrower/dashboard', [BorrowerDashboardController::class, 'index'])
        ->name('borrower.dashboard');

    Route::post('/borrower/reserve', [BorrowerDashboardController::class, 'reserve'])
        ->name('borrower.reserve');

    Route::delete('/borrower/reservation/{reservation}',
        [BorrowerDashboardController::class, 'cancelReservation'])
        ->name('borrower.cancelReservation');

    Route::get('/borrower/profile', [BorrowerProfileController::class, 'edit'])
        ->name('borrower.profile');

    Route::put('/borrower/profile', [BorrowerProfileController::class, 'update'])
        ->name('borrower.profile.update');

    Route::resource('reservations', ReservationController::class);
    Route::resource('donations', DonationController::class);
});
