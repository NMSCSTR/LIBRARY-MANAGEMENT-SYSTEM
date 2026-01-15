<?php
namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title'            => 'Harry Potter and the Philosopher\'s Stone',
            'isbn'             => '9780747532699',
            'year_published'   => '1997', // new field
            'place_published'  => 'London', // new field
            'author_id'        => Author::where('name', 'J.K. Rowling')->first()->id,
            'category_id'      => Category::where('name', 'Fiction')->first()->id,
            'publisher_id'     => Publisher::where('name', 'Penguin Books')->first()->id,
            'supplier_id'      => Supplier::first()->id,
            'copies_available' => 5,
        ]);

        Book::create([
            'title'            => '1984',
            'isbn'             => '9780451524935',
            'year_published'   => '1949', // new field
            'place_published'  => 'London', // new field
            'author_id'        => Author::where('name', 'George Orwell')->first()->id,
            'category_id'      => Category::where('name', 'Fiction')->first()->id,
            'publisher_id'     => Publisher::where('name', 'HarperCollins')->first()->id,
            'supplier_id'      => Supplier::first()->id,
            'copies_available' => 3,
        ]);
    }
}
