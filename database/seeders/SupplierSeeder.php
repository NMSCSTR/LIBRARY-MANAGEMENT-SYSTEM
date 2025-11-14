<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;


class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $suppliers = [
            ['name' => 'ABC Book Supplier'],
            ['name' => 'Knowledge Hub Distributors'],
            ['name' => 'Elite Publishing Partners'],
        ];

        foreach ($suppliers as $s) {
            Supplier::create($s);
        }
    }
}
