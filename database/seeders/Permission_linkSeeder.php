<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permission_linkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_links')->insert([
            'company_id' => 1,
            'permission_group_id' => 1,
            'permission_item_id' => 1
        ]);
    }
}
