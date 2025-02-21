<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Tamam Zulfa',
            'email' => 'tamam@tamam.com',
        ]);

        User::factory()->create([
            'name' => 'Sof Dev',
            'email' => 'sof@sof.com',
        ]);
    }
}
