<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['name' => 'Fiction', 'description' => 'Novels and literature'],
            ['name' => 'Science', 'description' => 'Scientific books'],
            ['name' => 'History', 'description' => 'Historical references'],
            ['name' => 'Technology', 'description' => 'IT, Computers, Engineering'],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
