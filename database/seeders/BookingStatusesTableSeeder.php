<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BookingStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('booking_statuses')->delete();
        
        \DB::table('booking_statuses')->insert(array (
            0 => 
            array (
                'created_at' => '2021-05-30 16:47:08',
                'id' => 1,
                'label' => 'Pending',
                'sequence' => 1,
                'status' => 1,
                'updated_at' => '2021-05-30 16:47:21',
                'value' => 'pending',
            ),
            1 => 
            array (
                'created_at' => '2021-05-30 16:50:40',
                'id' => 2,
                'label' => 'Accepted',
                'sequence' => 2,
                'status' => 1,
                'updated_at' => '2021-05-30 16:50:44',
                'value' => 'accept',
            ),
            2 => 
            array (
                'created_at' => '2021-05-30 16:50:46',
                'id' => 3,
                'label' => 'Ongoing',
                'sequence' => 3,
                'status' => 1,
                'updated_at' => '2021-05-30 16:50:48',
                'value' => 'on_going',
            ),
            3 => 
            array (
                'created_at' => '2021-05-30 16:50:50',
                'id' => 4,
                'label' => 'In Progress',
                'sequence' => 4,
                'status' => 1,
                'updated_at' => '2021-05-30 16:50:52',
                'value' => 'in_progress',
            ),
            4 => 
            array (
                'created_at' => '2021-05-30 16:50:54',
                'id' => 5,
                'label' => 'Hold',
                'sequence' => 5,
                'status' => 1,
                'updated_at' => '2021-05-30 16:50:56',
                'value' => 'hold',
            ),
            5 => 
            array (
                'created_at' => '2021-05-30 16:55:03',
                'id' => 6,
                'label' => 'Cancelled',
                'value' => 'cancelled',
                'sequence' => 6,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:05',
            ),
            6 => 
            array (
                'created_at' => '2021-05-30 16:55:09',
                'id' => 7,
                'label' => 'Rejected',
                'sequence' => 7,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:10',
                'value' => 'rejected',
            ),
            7 => 
            array (
                'created_at' => '2021-05-30 16:55:11',
                'id' => 8,
                'label' => 'Failed',
                'sequence' => 8,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:12',
                'value' => 'failed',
            ),
            8 => 
            array (
                'id' => 9,
                'label' => 'Completed',
                'value' => 'completed',
                'sequence' => 9,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:12',
                'created_at' => '2021-05-30 16:55:11',
            ),
            9 => 
            array (
                'id' => 10,
                'label' => 'Pending Approval',
                'value' => 'pending_approval',
                'sequence' => 10,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:12',
                'created_at' => '2021-05-30 16:55:11',
            ),
            10 => 
            array (
                'id' => 11,
                'label' => 'Waiting',
                'value' => 'waiting',
                'sequence' => 11,
                'status' => 1,
                'updated_at' => '2021-05-30 16:55:12',
                'created_at' => '2021-05-30 16:55:11',
            ),
        ));
        
        
    }
}