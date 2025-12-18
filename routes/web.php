<?php

use App\Http\Controllers\ActivityLogController;;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('users.logout');
});


    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('users.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('users.login.submit');

Route::get('/signup', [AuthController::class, 'showRegisterForm'])
    ->name('register.show');



    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');



Route::prefix('admin')->middleware(['auth', 'role:admin,librarian'])->group(function () {
    Route::get('/librarian/dashboard', function () {
            return view('librarian.dashboard');
        })->name('librarian.dashboard');

    Route::put('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])
    ->name('reservations.reject');



    Route::resource('authors', AuthorController::class)->names([
        'index'   => 'authors.index',
        'store'   => 'authors.store',
        'show'    => 'authors.show',
        'update'  => 'authors.update',
        'destroy' => 'authors.destroy',
    ]);



    Route::resource('books', BookController::class)->names([
        'index'   => 'books.index',
        'store'   => 'books.store',
        'show'    => 'books.show',
        'update'  => 'books.update',
        'destroy' => 'books.destroy',
    ]);


    Route::resource('categories', CategoryController::class)->names([
        'index'   => 'categories.index',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);


    Route::resource('publishers', PublisherController::class)->names([
        'index'   => 'publishers.index',
        'store'   => 'publishers.store',
        'show'    => 'publishers.show',
        'update'  => 'publishers.update',
        'destroy' => 'publishers.destroy',
    ]);


    Route::resource('suppliers', SupplierController::class)->names([
        'index'   => 'suppliers.index',
        'store'   => 'suppliers.store',
        'show'    => 'suppliers.show',
        'update'  => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);


    Route::resource('book-copies', BookCopyController::class)->names([
        'index'   => 'book-copies.index',
        'store'   => 'book-copies.store',
        'show'    => 'book-copies.show',
        'update'  => 'book-copies.update',
        'destroy' => 'book-copies.destroy',
    ]);


    Route::resource('borrows', BorrowController::class)->names([
        'index'   => 'borrows.index',
        'store'   => 'borrows.store',
        'show'    => 'borrows.show',
        'update'  => 'borrows.update',
        'destroy' => 'borrows.destroy',

    ]);
    Route::put('/borrows/{id}/return', [BorrowController::class, 'return'])->name('borrows.return');

    Route::resource('activity-logs', ActivityLogController::class)->names([
        'index'   => 'activity-logs.index',
        'store'   => 'activity-logs.store',
        'show'    => 'activity-logs.show',
        'update'  => 'activity-logs.update',
        'destroy' => 'activity-logs.destroy',
    ]);
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::put('/users/{id}/archive', [UserController::class, 'archive'])->name('users.archive');
    Route::put('/users/{user}/unarchive', [UserController::class, 'unarchive'])->name('users.unarchive');




    Route::resource('users', UserController::class)->names([
        'index'   => 'users.index',
        'store'   => 'users.store',
        'show'    => 'users.show',
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


Route::middleware(['auth'])->group(function () {

    Route::get('/instructor/dashboard', function () {
            return view('instructor.dashboard');
        })->name('instructor.dashboard');

    Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');

    Route::get('/borrower/dashboard', [BorrowerDashboardController::class, 'index'])
    ->name('borrower.dashboard');

    Route::get('/borrower-profile-view', [BorrowerProfileController::class, 'edit'])
        ->name('borrower.profile.view');

    Route::get('/borrower/profile', [BorrowerProfileController::class, 'edit'])
        ->name('borrower.profile');

    Route::put('/borrower/profile', [BorrowerProfileController::class, 'update'])
        ->name('borrower.profile.update');

    // Reserve a book copy
    Route::post('/borrower/reserve', [BorrowerDashboardController::class, 'reserve'])
        ->name('borrower.reserve');

    // Cancel a reservation
    Route::delete('/borrower/reservation/{reservation}', [BorrowerDashboardController::class, 'cancelReservation'])
        ->name('borrower.cancelReservation');

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
