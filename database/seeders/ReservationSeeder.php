<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Book;
use App\Models\BookCopy;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::whereHas('role', fn($q) => $q->where('name','student'))->get();
        $books = Book::with('copies')->get();

        foreach ($students as $student) {
            foreach ($books as $book) {
                // Pick a random available copy
                $copy = $book->copies->where('status', 'available')->first();

                if ($copy) {
                    // Create reservation
                    Reservation::create([
                        'user_id'     => $student->id,
                        'book_id'     => $book->id,
                        'copy_id'     => $copy->id, // assign the copy
                        'status'      => 'pending',
                        'reserved_at' => Carbon::now()->subDays(rand(0, 5)),
                    ]);

                    // Optionally mark the copy as reserved to avoid duplicates
                    $copy->update(['status' => 'reserved']);
                }
            }
        }
    }
}
