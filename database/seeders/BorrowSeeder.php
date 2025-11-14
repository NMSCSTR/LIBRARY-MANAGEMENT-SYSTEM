<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class BorrowSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role_id', function($q){
            $q->select('id')->from('roles')->where('name', 'student');
        })->get();

        $books = Book::all();

        foreach ($students as $student) {
            foreach ($books as $book) {
                Borrow::create([
                    'user_id' => $student->id,
                    'book_id' => $book->id,
                    'borrow_date' => Carbon::now()->subDays(rand(1, 10)),
                    'due_date' => Carbon::now()->addDays(rand(5, 15)),
                    'return_date' => null,
                    'status' => 'borrowed',
                ]);
            }
        }
    }
}
