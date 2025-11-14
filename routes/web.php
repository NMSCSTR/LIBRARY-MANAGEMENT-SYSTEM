<?php

use App\Http\Controllers\ActivityLogController;;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('users.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('users.login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('users.logout');
});



Route::prefix('admin')->middleware(['auth', 'role:admin,librarian'])->group(function () {
    Route::get('/librarian/dashboard', function () {
            return view('librarian.dashboard');
        })->name('librarian.dashboard');

    // Authors
    Route::resource('authors', AuthorController::class)->names([
        'index'   => 'authors.index',
        'store'   => 'authors.store',
        'show'    => 'authors.show',
        'update'  => 'authors.update',
        'destroy' => 'authors.destroy',
    ]);

    // Books
    Route::resource('books', BookController::class)->names([
        'index'   => 'books.index',
        'store'   => 'books.store',
        'show'    => 'books.show',
        'update'  => 'books.update',
        'destroy' => 'books.destroy',
    ]);

    // Categories
    Route::resource('categories', CategoryController::class)->names([
        'index'   => 'categories.index',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Publishers
    Route::resource('publishers', PublisherController::class)->names([
        'index'   => 'publishers.index',
        'store'   => 'publishers.store',
        'show'    => 'publishers.show',
        'update'  => 'publishers.update',
        'destroy' => 'publishers.destroy',
    ]);

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->names([
        'index'   => 'suppliers.index',
        'store'   => 'suppliers.store',
        'show'    => 'suppliers.show',
        'update'  => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);

    // Book Copies
    Route::resource('book-copies', BookCopyController::class)->names([
        'index'   => 'book-copies.index',
        'store'   => 'book-copies.store',
        'show'    => 'book-copies.show',
        'update'  => 'book-copies.update',
        'destroy' => 'book-copies.destroy',
    ]);

    // Borrows
    Route::resource('borrows', BorrowController::class)->names([
        'index'   => 'borrows.index',
        'store'   => 'borrows.store',
        'show'    => 'borrows.show',
        'update'  => 'borrows.update',
        'destroy' => 'borrows.destroy',
    ]);

    // Activity Logs
    Route::resource('activity-logs', ActivityLogController::class)->names([
        'index'   => 'activity-logs.index',
        'store'   => 'activity-logs.store',
        'show'    => 'activity-logs.show',
        'update'  => 'activity-logs.update',
        'destroy' => 'activity-logs.destroy',
    ]);
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

    Route::resource('users', UserController::class)->names([
        'index'   => 'users.index',
        'store'   => 'users.store',
        'show'    => 'users.show',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);


    Route::resource('roles', RoleController::class)->names([
        'index'   => 'roles.index',
        'store'   => 'roles.store',
        'show'    => 'roles.show',
        'update'  => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);
});

// Routes accessible to all authenticated users
Route::middleware(['auth'])->group(function () {

    // Instructor Dashboard
    Route::get('/instructor/dashboard', function () {
            return view('instructor.dashboard');
        })->name('instructor.dashboard');

    // Student Dashboard
    Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');
        
    // Donor Dashboard
    Route::get('/donor/dashboard', function () {
            return view('donor.dashboard');
        })->name('donor.dashboard');

    Route::resource('reservations', ReservationController::class)->names([
        'index'   => 'reservations.index',
        'store'   => 'reservations.store',
        'show'    => 'reservations.show',
        'update'  => 'reservations.update',
        'destroy' => 'reservations.destroy',
    ]);

    Route::resource('donations', DonationController::class)->names([
        'index'   => 'donations.index',
        'store'   => 'donations.store',
        'show'    => 'donations.show',
        'update'  => 'donations.update',
        'destroy' => 'donations.destroy',
    ]);
});
