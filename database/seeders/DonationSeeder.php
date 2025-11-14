<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\User;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors = User::whereHas('role', fn($q) => $q->where('name','donor'))->get();

        foreach ($donors as $donor) {
            Donation::create([
                'donor_id' => $donor->id,
                'book_title' => 'Donated Book ' . rand(1, 20),
                'author' => 'Author ' . rand(1, 10),
                'publisher' => 'Publisher ' . rand(1, 5),
                'year_published' => rand(2000, 2025),
                'quantity' => rand(1, 5),
                'status' => 'pending',
            ]);
        }
    }
}
