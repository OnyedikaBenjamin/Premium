<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'role',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-06-04 06:06:03',
                'updated_at' => '2021-06-04 06:06:03',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'role add',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => '2021-06-04 06:58:07',
                'updated_at' => '2021-06-04 06:58:07',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'role list',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => '2021-06-04 06:58:22',
                'updated_at' => '2021-06-04 06:58:22',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'permission',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-06-04 06:58:47',
                'updated_at' => '2021-06-04 06:58:47',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'permission add',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => '2021-06-04 06:58:59',
                'updated_at' => '2021-06-04 06:58:59',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'permission list',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => '2021-06-04 06:59:54',
                'updated_at' => '2021-06-04 06:59:54',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'category',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-06-08 03:57:14',
                'updated_at' => '2021-06-08 03:57:14',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'category list',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2021-06-08 03:57:27',
                'updated_at' => '2021-06-08 03:57:27',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'category add',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2021-06-08 03:57:39',
                'updated_at' => '2021-06-08 03:57:39',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'category edit',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2021-06-08 04:11:52',
                'updated_at' => '2021-06-08 04:11:52',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'category delete',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => '2021-06-08 04:12:03',
                'updated_at' => '2021-06-08 04:12:03',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'service',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 04:17:18',
                'updated_at' => '2021-07-13 04:17:18',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'service list',
                'guard_name' => 'web',
                'parent_id' => 12,
                'created_at' => '2021-07-13 04:17:34',
                'updated_at' => '2021-07-13 04:17:34',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'service add',
                'guard_name' => 'web',
                'parent_id' => 12,
                'created_at' => '2021-07-13 04:17:48',
                'updated_at' => '2021-07-13 04:17:48',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'service edit',
                'guard_name' => 'web',
                'parent_id' => 12,
                'created_at' => '2021-07-13 04:18:07',
                'updated_at' => '2021-07-13 04:18:07',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'service delete',
                'guard_name' => 'web',
                'parent_id' => 12,
                'created_at' => '2021-07-13 04:18:22',
                'updated_at' => '2021-07-13 04:18:22',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'provider',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 04:57:48',
                'updated_at' => '2021-07-13 04:57:48',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'provider list',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2021-07-13 04:57:59',
                'updated_at' => '2021-07-13 04:57:59',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'provider add',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2021-07-13 04:58:14',
                'updated_at' => '2021-07-13 04:58:14',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'provider edit',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2021-07-13 04:58:30',
                'updated_at' => '2021-07-13 04:58:30',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'provider delete',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2021-07-13 04:58:46',
                'updated_at' => '2021-07-13 04:58:46',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'handyman',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 04:59:30',
                'updated_at' => '2021-07-13 04:59:30',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'handyman list',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2021-07-13 05:00:40',
                'updated_at' => '2021-07-13 05:00:40',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'handyman add',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2021-07-13 05:00:53',
                'updated_at' => '2021-07-13 05:00:53',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'handyman edit',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2021-07-13 05:01:07',
                'updated_at' => '2021-07-13 05:01:07',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'handyman delete',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2021-07-13 05:01:20',
                'updated_at' => '2021-07-13 05:01:20',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'booking',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 05:11:44',
                'updated_at' => '2021-07-13 05:11:44',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'booking list',
                'guard_name' => 'web',
                'parent_id' => 27,
                'created_at' => '2021-07-13 05:11:56',
                'updated_at' => '2021-07-13 05:11:56',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'booking edit',
                'guard_name' => 'web',
                'parent_id' => 27,
                'created_at' => '2021-07-13 05:12:05',
                'updated_at' => '2021-07-13 05:12:05',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'booking delete',
                'guard_name' => 'web',
                'parent_id' => 27,
                'created_at' => '2021-07-13 05:13:27',
                'updated_at' => '2021-07-13 05:13:27',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'booking view',
                'guard_name' => 'web',
                'parent_id' => 27,
                'created_at' => '2021-07-13 06:25:02',
                'updated_at' => '2021-07-13 06:25:02',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'payment',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-08-05 07:09:53',
                'updated_at' => '2021-08-05 07:09:53',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'payment list',
                'guard_name' => 'web',
                'parent_id' => 32,
                'created_at' => '2021-08-05 07:10:29',
                'updated_at' => '2021-08-05 07:10:29',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'user',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-08-05 07:12:02',
                'updated_at' => '2021-08-05 07:12:02',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'user list',
                'guard_name' => 'web',
                'parent_id' => 34,
                'created_at' => '2021-08-05 07:12:17',
                'updated_at' => '2021-08-05 07:12:17',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'user view',
                'guard_name' => 'web',
                'parent_id' => 34,
                'created_at' => '2021-08-05 07:13:56',
                'updated_at' => '2021-08-05 07:13:56',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'user delete',
                'guard_name' => 'web',
                'parent_id' => 34,
                'created_at' => '2021-08-05 07:14:08',
                'updated_at' => '2021-08-05 07:14:08',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'providertype',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-08-11 03:35:29',
                'updated_at' => '2021-08-11 03:35:29',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'providertype list',
                'guard_name' => 'web',
                'parent_id' => 38,
                'created_at' => '2021-08-11 03:35:42',
                'updated_at' => '2021-08-11 03:35:42',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'providertype add',
                'guard_name' => 'web',
                'parent_id' => 38,
                'created_at' => '2021-08-11 03:35:57',
                'updated_at' => '2021-08-11 03:35:57',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'providertype edit',
                'guard_name' => 'web',
                'parent_id' => 38,
                'created_at' => '2021-08-11 03:36:39',
                'updated_at' => '2021-08-11 03:36:39',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'providertype delete',
                'guard_name' => 'web',
                'parent_id' => 38,
                'created_at' => '2021-08-11 03:39:22',
                'updated_at' => '2021-08-11 03:39:22',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'coupon',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-08-11 08:10:38',
                'updated_at' => '2021-08-11 08:10:38',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'coupon list',
                'guard_name' => 'web',
                'parent_id' => 43,
                'created_at' => '2021-08-11 08:10:47',
                'updated_at' => '2021-08-11 08:10:47',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'coupon add',
                'guard_name' => 'web',
                'parent_id' => 43,
                'created_at' => '2021-08-11 08:11:02',
                'updated_at' => '2021-08-11 08:11:02',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'coupon edit',
                'guard_name' => 'web',
                'parent_id' => 43,
                'created_at' => '2021-08-11 08:11:17',
                'updated_at' => '2021-08-11 08:11:17',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'coupon delete',
                'guard_name' => 'web',
                'parent_id' => 43,
                'created_at' => '2021-08-11 08:11:27',
                'updated_at' => '2021-08-11 08:11:27',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'slider',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-08-12 06:38:52',
                'updated_at' => '2021-08-12 06:38:52',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'slider list',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2021-08-12 06:39:05',
                'updated_at' => '2021-08-12 06:39:05',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'slider add',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2021-08-12 06:39:17',
                'updated_at' => '2021-08-12 06:39:17',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'slider edit',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2021-08-12 06:39:26',
                'updated_at' => '2021-08-12 06:39:26',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'slider delete',
                'guard_name' => 'web',
                'parent_id' => 48,
                'created_at' => '2021-08-12 06:39:37',
                'updated_at' => '2021-08-12 06:39:37',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'pending provider',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2021-09-28 06:39:26',
                'updated_at' => '2021-09-28 06:39:26',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'pending handyman',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'pages',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'terms condition',
                'guard_name' => 'web',
                'parent_id' => 55,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'privacy policy',
                'guard_name' => 'web',
                'parent_id' => 55,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'provider address',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'provideraddress list',
                'guard_name' => 'web',
                'parent_id' => 58,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'provideraddress add',
                'guard_name' => 'web',
                'parent_id' => 58,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'provideraddress edit',
                'guard_name' => 'web',
                'parent_id' => 58,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'provideraddress delete',
                'guard_name' => 'web',
                'parent_id' => 58,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'document',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-10-27 06:00:09',
                'updated_at' => '2021-10-27 06:00:09',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'document list',
                'guard_name' => 'web',
                'parent_id' => 63,
                'created_at' => '2021-10-27 06:00:24',
                'updated_at' => '2021-10-27 06:00:24',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'document add',
                'guard_name' => 'web',
                'parent_id' => 63,
                'created_at' => '2021-10-27 06:00:38',
                'updated_at' => '2021-10-27 06:00:38',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'document edit',
                'guard_name' => 'web',
                'parent_id' => 63,
                'created_at' => '2021-10-27 06:00:56',
                'updated_at' => '2021-10-27 06:00:56',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'document delete',
                'guard_name' => 'web',
                'parent_id' => 63,
                'created_at' => '2021-10-27 06:01:11',
                'updated_at' => '2021-10-27 06:01:11',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'provider document',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-10-27 10:32:48',
                'updated_at' => '2021-10-27 10:32:48',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'providerdocument list',
                'guard_name' => 'web',
                'parent_id' => 68,
                'created_at' => '2021-10-27 10:33:05',
                'updated_at' => '2021-10-27 10:33:05',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'providerdocument add',
                'guard_name' => 'web',
                'parent_id' => 68,
                'created_at' => '2021-10-27 10:33:20',
                'updated_at' => '2021-10-27 10:33:20',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'providerdocument edit',
                'guard_name' => 'web',
                'parent_id' => 68,
                'created_at' => '2021-10-27 10:33:32',
                'updated_at' => '2021-10-27 10:33:32',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'providerdocument delete',
                'guard_name' => 'web',
                'parent_id' => 68,
                'created_at' => '2021-10-27 10:33:51',
                'updated_at' => '2021-10-27 10:33:51',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'provider payout',
                'guard_name' => 'web',
                'parent_id' => 17,
                'created_at' => '2022-01-21 04:46:07',
                'updated_at' => '2022-01-21 04:46:07',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'handyman payout',
                'guard_name' => 'web',
                'parent_id' => 22,
                'created_at' => '2022-02-14 04:46:07',
                'updated_at' => '2022-02-14 04:46:07',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'servicefaq',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'servicefaq add',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'servicefaq edit',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'servicefaq delete',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'servicefaq list',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'user add',
                'guard_name' => 'web',
                'parent_id' => 34,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'user edit',
                'guard_name' => 'web',
                'parent_id' => 34,
                'created_at' => '2022-02-19 04:46:07',
                'updated_at' => '2022-02-19 04:46:07',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'subcategory',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'subcategory add',
                'guard_name' => 'web',
                'parent_id' => 82,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'subcategory edit',
                'guard_name' => 'web',
                'parent_id' => 82,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'subcategory delete',
                'guard_name' => 'web',
                'parent_id' => 82,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'subcategory list',
                'guard_name' => 'web',
                'parent_id' => 82,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'handymantype',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 04:59:30',
                'updated_at' => '2021-07-13 04:59:30',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'handymantype list',
                'guard_name' => 'web',
                'parent_id' => 87,
                'created_at' => '2021-07-13 05:00:40',
                'updated_at' => '2021-07-13 05:00:40',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'handymantype add',
                'guard_name' => 'web',
                'parent_id' => 87,
                'created_at' => '2021-07-13 05:00:53',
                'updated_at' => '2021-07-13 05:00:53',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'handymantype edit',
                'guard_name' => 'web',
                'parent_id' => 87,
                'created_at' => '2021-07-13 05:01:07',
                'updated_at' => '2021-07-13 05:01:07',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'handymantype delete',
                'guard_name' => 'web',
                'parent_id' => 87,
                'created_at' => '2021-07-13 05:01:20',
                'updated_at' => '2021-07-13 05:01:20',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'postjob',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2021-07-13 05:01:20',
                'updated_at' => '2021-07-13 05:01:20',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'postjob list',
                'guard_name' => 'web',
                'parent_id' => 92,
                'created_at' => '2021-07-13 05:01:20',
                'updated_at' => '2021-07-13 05:01:20',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'servicepackage',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'servicepackage add',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'servicepackage edit',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'servicepackage delete',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'servicepackage list',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'Help and support',
                'guard_name' => 'web',
                'parent_id' => 55,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'Refund and Cancellation Policy',
                'guard_name' => 'web',
                'parent_id' => 55,
                'created_at' => '2021-09-28 06:39:37',
                'updated_at' => '2021-09-28 06:39:37',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'blog',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'blog add',
                'guard_name' => 'web',
                'parent_id' => 101,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'blog edit',
                'guard_name' => 'web',
                'parent_id' => 101,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'blog delete',
                'guard_name' => 'web',
                'parent_id' => 101,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'blog list',
                'guard_name' => 'web',
                'parent_id' => 101,
                'created_at' => '2022-04-01 04:46:07',
                'updated_at' => '2022-04-01 04:46:07',
            ),
        ));
        
        
    }
}