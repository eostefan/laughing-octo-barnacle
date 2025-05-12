<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
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
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        Account::factory(1)->create([
            'user_id' => 1,
            'type' => 'credit'
        ]);

        Account::factory(1)->create([
            'user_id' => 1,
            'type' => 'debit'
        ]);

        Account::factory(1)->create([
            'user_id' => 2,
            'type' => 'debit'
        ]);

        Account::factory(1)->create([
            'user_id' => 2,
            'type' => 'credit'
        ]);

        Transaction::factory(5)->create([
            'account_id' => 1,
            'initial_account_id' => 1,
            'type' => 'credit'
        ]);

        Transaction::factory(5)->create([
            'account_id' => 2,
            'initial_account_id' => 2,
            'type' => 'debit'
        ]);

        Transaction::factory(5)->create([
            'account_id' => 3,
            'initial_account_id' => 3,
            'type' => 'credit'
        ]);

        Transaction::factory(5)->create([
            'account_id' => 4,
            'initial_account_id' => 4,
            'type' => 'debit'
        ]);

    }
}
