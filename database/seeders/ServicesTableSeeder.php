<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('services')->delete();
        
        \DB::table('services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Retail Shop pest control and Disinfection of entire premises',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 500.0,
                'type' => 'fixed',
                'duration' => '12:00',
                'discount' => 10.0,
                'status' => 1,
                'description' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:36:09',
                'updated_at' => '2022-02-23 10:08:03',
                'subcategory_id' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Fixing Anroid Smart Devices around Interior and Wiring',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 150.0,
                'type' => 'hourly',
                'duration' => '01:00',
                'discount' => NULL,
                'status' => 1,
                'description' => 'Open your Settings app and tap Network & internet or Connections. Depending on your device, these options may be different.',
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:51:09',
                'updated_at' => '2022-02-23 10:08:18',
                'subcategory_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Black and White Spot in display and blur Images',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 1000.0,
                'type' => 'fixed',
                'duration' => '05:00',
                'discount' => 30.0,
                'status' => 1,
                'description' => 'The LCD screen is made with high-precision technology to achieve a high level of performance and picture quality. To achieve this level of performance, the backlight setting of the TV is set to maximise the screen brightness.',
                'is_featured' => 1,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:53:58',
                'updated_at' => '2022-02-23 11:55:47',
                'subcategory_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Uninstallation and Flickering TV Display Screen',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 700.0,
                'type' => 'hourly',
                'duration' => '03:00',
                'discount' => NULL,
                'status' => 1,
                'description' => 'A blinking or flickering television can occur randomly. This dimming TV screen effect can seem like a strobe light and show flashes of black.',
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 06:56:19',
                'updated_at' => '2022-02-23 10:08:36',
                'subcategory_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Scalp Facial',
                'category_id' => 8,
                'provider_id' => 4,
                'price' => 1200.0,
                'type' => 'fixed',
                'duration' => '02:30',
                'discount' => 20.0,
                'status' => 1,
                'description' => 'Whether your fine hair is weighed down by an oily scalp or prone to flaky patches, a healthy scalp is essential if you want healthy hair.',
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 07:04:40',
                'updated_at' => '2022-02-23 11:54:45',
                'subcategory_id' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Funky Paints Painting',
                'category_id' => 3,
                'provider_id' => 4,
                'price' => 150.0,
                'type' => 'hourly',
                'duration' => '10:00',
                'discount' => 0.0,
                'status' => 1,
                'description' => 'We take you through every step of the painting process, from the initial consultation up to the unveiling of the project. Aside from all these, we also provide an exceptional level of communication.',
                'is_featured' => 1,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-02-23 07:08:26',
                'updated_at' => '2022-03-04 04:52:22',
                'subcategory_id' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Rainbow Cleaners',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 600.0,
                'type' => 'fixed',
                'duration' => '12:00',
                'discount' => 10.0,
                'status' => 1,
                'description' => NULL,
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-03-02 09:02:29',
                'updated_at' => '2022-03-02 09:02:29',
                'subcategory_id' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Spray Sanitizer',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 1000.0,
                'type' => 'hourly',
                'duration' => '02:00',
                'discount' => 20.0,
                'status' => 1,
                'description' => NULL,
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-03-02 09:15:59',
                'updated_at' => '2022-03-02 09:15:59',
                'subcategory_id' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Electrical repairs, rewires, and remodels',
                'category_id' => 2,
                'provider_id' => 4,
                'price' => 600.0,
                'type' => 'fixed',
                'duration' => '12:00',
                'discount' => 10.0,
                'status' => 1,
                'description' => NULL,
                'is_featured' => 1,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-03-02 09:49:22',
                'updated_at' => '2022-03-02 09:49:22',
                'subcategory_id' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'drain cleaning plumbing services',
                'category_id' => 1,
                'provider_id' => 4,
                'price' => 1500.0,
                'type' => 'fixed',
                'duration' => '12:00',
                'discount' => 10.0,
                'status' => 1,
                'description' => NULL,
                'is_featured' => 0,
                'added_by' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-03-02 10:04:25',
                'updated_at' => '2022-03-02 10:04:25',
                'subcategory_id' => NULL,
            ),
        ));
        
        
    }
}