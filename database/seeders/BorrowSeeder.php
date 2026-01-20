<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Book;
use App\Models\BookCopy;
use Carbon\Carbon;

class BorrowSeeder extends Seeder
{
    public function run(): void
    {
        // Get students and books
        $students = User::whereHas('role', function($q) {
            $q->where('name', 'student');
        })->get();

        $books = Book::with('copies')->get();

        if ($students->isEmpty() || $books->isEmpty()) {
            return;
        }

        foreach ($students as $index => $student) {
            $book = $books->random();
            $copy = $book->copies->first();

            if (!$copy) continue;

            // Scenario 1: On-Time Return (No Penalty)
            // Borrowed 5 days ago, Due in 2 days, Returned 4 days ago.
            Borrow::create([
                'user_id' => $student->id,
                'book_id' => $book->id,
                'book_copy_id' => $copy->id,
                'borrow_date' => Carbon::now('Asia/Manila')->subDays(5),
                'due_date' => Carbon::now('Asia/Manila')->subDays(2),
                'return_date' => Carbon::now('Asia/Manila')->subDays(3), // Returned before due date
                'status' => 'returned',
            ]);

            // Scenario 2: Late Return (Penalty Badge should appear)
            // Borrowed 10 days ago, Due 7 days ago, Returned yesterday.
            $lateBook = $books->skip(1)->first() ?? $book;
            $lateCopy = $lateBook->copies->last();
            Borrow::create([
                'user_id' => $student->id,
                'book_id' => $lateBook->id,
                'book_copy_id' => $lateCopy->id,
                'borrow_date' => Carbon::now('Asia/Manila')->subDays(10),
                'due_date' => Carbon::now('Asia/Manila')->subDays(7),
                'return_date' => Carbon::now('Asia/Manila')->subDays(1), // Returned 6 days late
                'status' => 'returned',
            ]);

            // Scenario 3: Active Overdue (Pulsing Penalty Alert)
            // Borrowed 4 days ago, Due 1 day ago, Not yet returned.
            $overdueBook = $books->last();
            $overdueCopy = $overdueBook->copies->first();
            Borrow::create([
                'user_id' => $student->id,
                'book_id' => $overdueBook->id,
                'book_copy_id' => $overdueCopy->id,
                'borrow_date' => Carbon::now('Asia/Manila')->subDays(4),
                'due_date' => Carbon::now('Asia/Manila')->subDays(1), // Past due
                'return_date' => null,
                'status' => 'borrowed', // Logic in Model will display this as 'overdue'
            ]);

            // Scenario 4: Active Normal Borrow (No Penalty)
            // Borrowed today, Due in 3 days.
            $normalBook = $books->random();
            $normalCopy = $normalBook->copies->last();
            Borrow::create([
                'user_id' => $student->id,
                'book_id' => $normalBook->id,
                'book_copy_id' => $normalCopy->id,
                'borrow_date' => Carbon::now('Asia/Manila'),
                'due_date' => Carbon::now('Asia/Manila')->addDays(3),
                'return_date' => null,
                'status' => 'borrowed',
            ]);
        }
    }
}
