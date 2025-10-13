<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            // fake user to auth the application
            User::factory()->create(['name' => 'FlyStack', 'email' => 'flystack@example.com']);

            return;
        }

        $this->call([
            UserSeeder::class,
        ]);
    }
}
