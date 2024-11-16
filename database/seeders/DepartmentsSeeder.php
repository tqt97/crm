<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        Department::create(
            [
                'name' => 'HR',
            ]
        );
        // Fake 10
        Department::factory(10)->create();
    }
}
