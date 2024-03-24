<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostRequestStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('post_request_statuses')->delete();
        \DB::table('post_request_statuses')->insert(array (
            0 => 
            array (
                'created_at' => '2021-05-30 16:47:08',
                'id' => 1,
                'label' => 'Requested',
                'sequence' => 1,
                'status' => 1,
                'updated_at' => '2021-05-30 16:47:21',
                'value' => 'requested',
            ),
            1 => 
            array (
                'created_at' => '2021-05-30 16:50:40',
                'id' => 2,
                'label' => 'Accepted',
                'sequence' => 2,
                'status' => 1,
                'updated_at' => '2021-05-30 16:50:44',
                'value' => 'accepted',
            ),

            2 => 
            array (
                'created_at' => '2021-05-30 16:55:03',
                'id' => 3,
                'label' => 'Cancelled',
                'value' => 'cancelled',
                'sequence' => 3,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:05',
            ),
            3 => 
            array (
                'created_at' => '2021-05-30 16:55:09',
                'id' => 4,
                'label' => 'Declined',
                'sequence' => 4,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:10',
                'value' => 'declined',
            ),
           4 => 
            array (
                'created_at' => '2021-05-30 16:55:09',
                'id' => 5,
                'label' => 'Assigned',
                'sequence' => 5,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:10',
                'value' => 'assigned',
            )
        ));
    }
}
