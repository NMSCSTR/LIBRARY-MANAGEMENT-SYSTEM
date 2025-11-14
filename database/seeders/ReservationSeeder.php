<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::whereHas('role', fn($q) => $q->where('name','student'))->get();
        $books = Book::all();

        foreach ($students as $student) {
            foreach ($books as $book) {
                Reservation::create([
                    'user_id' => $student->id,
                    'book_id' => $book->id,
                    'status' => 'pending',
                    'reserved_at' => Carbon::now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }
}
