<?php
namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Borrow;
use App\Models\Donation;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        // Borrow logs
        Borrow::all()->each(function ($borrow) {
            ActivityLog::create([
                'user_id'     => $borrow->user_id,
                'action'      => 'borrowed book',
                'description' => "Borrowed '{$borrow->book->title}' on {$borrow->borrow_date} due {$borrow->due_date}",
            ]);
        });

        // Reservation logs
        Reservation::all()->each(function ($reservation) {
            ActivityLog::create([
                'user_id'     => $reservation->user_id,
                'action'      => 'reserved book',
                'description' => "Reserved '{$reservation->book->title}' at {$reservation->reserved_at}",
            ]);
        });

        // Donation logs
        Donation::all()->each(function ($donation) {
            ActivityLog::create([
                'user_id'     => $donation->donor_id,
                'action'      => 'donated book',
                'description' => "Donated '{$donation->book_title}' (Qty: {$donation->quantity}) Status: {$donation->status}",
            ]);
        });
    }
}
