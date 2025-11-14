<?php
namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'Pearson Publishing', 'email' => 'info@pearson.com'],
            ['name' => 'O\'Reilly Media', 'email' => 'contact@oreilly.com'],
            ['name' => 'McGraw-Hill', 'email' => 'service@mcgrawhill.com'],

            // Added so BookSeeder does NOT fail
            ['name' => 'Penguin Books', 'email' => 'support@penguin.com'],
            ['name' => 'HarperCollins', 'email' => 'info@harpercollins.com'],
        ];

        foreach ($publishers as $pub) {
            Publisher::create($pub);
        }
    }
}
