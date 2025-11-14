<?php
namespace Database\Seeders;

use App\Models\BookCopy;
use Illuminate\Database\Seeder;


class BookCopySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 10; $i++) {
            BookCopy::create([
                'book_id'        => 1,
                'copy_number'    => $i,
                'status'         => 'available',
                'shelf_location' => 'Shelf A-' . $i,
            ]);
        }
    }
}
