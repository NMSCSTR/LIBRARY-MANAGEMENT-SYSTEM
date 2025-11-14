<?php
namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
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
