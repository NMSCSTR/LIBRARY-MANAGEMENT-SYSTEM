<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin      = Role::where('name', 'admin')->first();
        $librarian  = Role::where('name', 'librarian')->first();
        $student    = Role::where('name', 'student')->first();
        $instructor = Role::where('name', 'instructor')->first();
        $donor      = Role::where('name', 'donor')->first();

        User::create([
            'role_id'  => $admin->id,
            'name'     => 'System Admin',
            'email'    => 'admin@lms.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'role_id'  => $librarian->id,
            'name'     => 'Main Librarian',
            'email'    => 'a',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'role_id'  => $student->id,
            'name'     => 'John Student',
            'email'    => 'student@lms.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'role_id'  => $instructor->id,
            'name'     => 'Jane Instructor',
            'email'    => 'instructor@lms.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'role_id'  => $donor->id,
            'name'     => 'Peter Donor',
            'email'    => 'donor@lms.com',
            'password' => Hash::make('password'),
        ]);
    }
}
