<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permission_itemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_items')->insert([
            'company_id' => 1,
            'name' => 'developer',
            'slug' => 'developer'
        ]);
    }
}
