<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author; 

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            'J.K. Rowling',
            'George Orwell',
            'Mark Twain',
            'Paulo Coelho',
            'Stephen King',
        ];

        foreach ($authors as $name) {
            Author::create(['name' => $name]);
        }
    }
}
