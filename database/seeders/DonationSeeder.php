<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;
use App\Models\Author;
use App\Models\Publisher;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors = User::whereHas('role', fn($q) => $q->where('name', 'donor'))->get();

        // Get authors & publishers
        $authors = Author::pluck('id')->toArray();
        $publishers = Publisher::pluck('id')->toArray();

        foreach ($donors as $donor) {
            Donation::create([
                'donor_id'       => $donor->id,
                'book_title'     => 'Donated Book ' . rand(1, 20),
                'author_id'      => fake()->randomElement($authors),
                'publisher_id'   => fake()->randomElement($publishers),
                'year_published' => rand(2000, 2025),
                'quantity'       => rand(1, 5),
                'status'         => 'pending',
            ]);
        }
    }
}
