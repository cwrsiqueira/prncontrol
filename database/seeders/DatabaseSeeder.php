<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            CompanySeeder::class,
            Permission_groupSeeder::class,
            Permission_itemSeeder::class,
            Permission_linkSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            AddressSeeder::class,
            ContactSeeder::class,
        ]);
    }
}