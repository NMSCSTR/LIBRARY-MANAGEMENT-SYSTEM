<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Roles (foundation of user access)
        $this->call(RoleSeeder::class);

        // 2️⃣ Users (requires roles)
        $this->call(UserSeeder::class);

        // 3️⃣ Categories, Authors, Publishers, Suppliers
        $this->call(CategorySeeder::class);
        $this->call(AuthorSeeder::class);
        $this->call(PublisherSeeder::class);
        $this->call(SupplierSeeder::class);

        // 4️⃣ Books (requires authors, categories, suppliers, publishers)
        $this->call(BookSeeder::class);

        // 5️⃣ Book Copies (requires books)
        $this->call(BookCopySeeder::class);

        // 6️⃣ Borrow Records (requires users and books)
        $this->call(BorrowSeeder::class);

        // 7️⃣ Reservations (requires users and books)
        $this->call(ReservationSeeder::class);

        // 8️⃣ Donations (requires users)
        $this->call(DonationSeeder::class);

        // 9️⃣ Activity Logs
        $this->call(ActivityLogSeeder::class);

    }
}
