<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlidersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sliders')->delete();
        
        \DB::table('sliders')->insert(array (
            0 => 
            array (
                'created_at' => '2022-02-23 09:05:27',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 1,
                'status' => 1,
                'title' => 'Retail Shop pest control and Disinfection of entire premises',
                'type' => 'service',
                'type_id' => '1',
                'updated_at' => '2022-02-23 09:16:33',
            ),
            1 => 
            array (
                'created_at' => '2022-02-23 09:21:08',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 2,
                'status' => 1,
                'title' => 'Fixing Anroid Smart Devices around Interior and Wiring',
                'type' => 'service',
                'type_id' => '2',
                'updated_at' => '2022-02-23 09:21:08',
            ),
            2 => 
            array (
                'created_at' => '2022-02-23 09:23:23',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 3,
                'status' => 1,
                'title' => 'Black and White Spot in display and blur Images',
                'type' => 'service',
                'type_id' => '3',
                'updated_at' => '2022-02-23 09:23:23',
            ),
            3 => 
            array (
                'created_at' => '2022-02-23 09:25:11',
                'deleted_at' => NULL,
                'description' => NULL,
                'id' => 4,
                'status' => 1,
                'title' => 'Uninstallation and Flickering TV Display Screen',
                'type' => 'service',
                'type_id' => '4',
                'updated_at' => '2022-02-23 09:25:11',
            ),
        ));
        
        
    }
}