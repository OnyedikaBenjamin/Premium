<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Plumber',
                'description' => 'Responsibilities include repairing pipes, tanks, and water fixtures, unclogging toilets and drains, and handling various residential plumbing requirements. You should also be able to diagnose problems, troubleshoot systems, and collaborate with other contractors when required.',
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 05:22:59',
                'updated_at' => '2022-03-03 05:19:52',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Smart Home',
            'description' => 'A smart home is "a residence equipped with a high-tech network, linking sensors and domestic devices, appliances, and features that can be remotely monitored, accessed or controlled, and provide services that respond to the needs of its inhabitants" (Balta-Ozkan et al., 2013).',
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:27:43',
                'updated_at' => '2022-03-03 05:20:38',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Painter',
                'description' => 'Able to apply paint, stain, varnish, enamel etc. to property walls, ceilings, and furniture. Able to effectively use brushes, spray guns, or rollers. Responsible for maintaining property colour scheme specifications',
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:29:36',
                'updated_at' => '2022-03-03 05:20:55',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Pest Control',
                'description' => 'Pest control is the regulation or management of a species defined as a pest, a member of the animal kingdom that impacts adversely on human activities',
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:39:07',
                'updated_at' => '2022-03-03 05:21:27',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Carpenter',
                'description' => 'Carpenters construct, install, and repair a variety of residential, commercial, and industrial structures and fixtures. In general, carpenters work with wood, steel, and concrete. Carpenters are also often involved with demolition and maintenance of these structures and fixtures.',
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:39:54',
                'updated_at' => '2022-03-03 05:22:33',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Security',
                'description' => NULL,
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:40:23',
                'updated_at' => '2022-02-23 06:40:26',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'AC Repair',
                'description' => NULL,
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:40:59',
                'updated_at' => '2022-02-23 06:41:02',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Salon',
                'description' => NULL,
                'color' => '#000000',
                'status' => 1,
                'is_featured' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:41:17',
                'updated_at' => '2022-02-23 06:41:21',
            )
        ));
        
        
    }
}