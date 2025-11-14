<?php
namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $publishers = [
            ['name' => 'Pearson Publishing', 'email' => 'info@pearson.com'],
            ['name' => 'O\'Reilly Media', 'email' => 'contact@oreilly.com'],
            ['name' => 'McGraw-Hill', 'email' => 'service@mcgrawhill.com'],
        ];

        foreach ($publishers as $pub) {
            Publisher::create($pub);
        }
    }
}
