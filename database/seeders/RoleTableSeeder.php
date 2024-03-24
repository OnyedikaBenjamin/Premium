<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => '2021-06-03 12:23:01',
                'updated_at' => '2021-06-03 12:23:03',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'demo_admin',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => '2021-06-03 12:23:16',
                'updated_at' => '2021-06-04 10:24:13',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'user',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => '2021-06-04 10:31:46',
                'updated_at' => '2021-06-04 10:31:46',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'provider',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => '2021-06-04 10:31:46',
                'updated_at' => '2021-06-04 10:31:46',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'handyman',
                'guard_name' => 'web',
                'status' => 1,
                'created_at' => '2021-06-04 10:31:46',
                'updated_at' => '2021-06-04 10:31:46',
            ),
        ));
        
        
    }
}