<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create(
            [
                'uuid' => Str::uuid(),
                'email' => 'admin@thk-hd.vn',
                'password' => 'admin',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'remember_token' => Str::random(10),
                'type' => 1
            ]
        );

        // assign Administrator role
        $user->assignRole('Administrator');

        // Fake 100 users
        User::factory(100)->create()->each(function ($user) {
            // assign Employee role
            $user->assignRole('Employee');
        });
    }
}
