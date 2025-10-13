<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        User::factory()->create(['name' => 'FlyStack', 'email' => 'flystack@example.com']);

        $now = now();
        $pwdHash = Hash::make('password');
        $total = 10_000;
        $chunk = 1_000;

        LazyCollection::times($total)
            ->map(fn (int $index) => [
                'name' => fake()->name(),
                'email' => 'seed+'.$index.'@example.com',
                'password' => $pwdHash,
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->chunk($chunk)
            ->each(fn (LazyCollection $c) => DB::table('users')->insertOrIgnore($c->all()));

        DB::enableQueryLog();
    }
}
