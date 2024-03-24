<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('model_has_roles')->delete();
        
        \DB::table('model_has_roles')->insert(array (
            0 => 
            array (
                'model_id' => 1,
                'model_type' => 'App\\Models\\User',
                'role_id' => 1,
            ),
            1 => 
            array (
                'model_id' => 2,
                'model_type' => 'App\\Models\\User',
                'role_id' => 2,
            ),
            2 => 
            array (
                'model_id' => 3,
                'model_type' => 'App\\Models\\User',
                'role_id' => 3,
            ),
            3 => 
            array (
                'model_id' => 4,
                'model_type' => 'App\\Models\\User',
                'role_id' => 4,
            ),
            4 => 
            array (
                'model_id' => 5,
                'model_type' => 'App\\Models\\User',
                'role_id' => 5,
            ),
        ));
        
        
    }
}