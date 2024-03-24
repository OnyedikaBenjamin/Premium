<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app_settings')->delete();
        
        \DB::table('app_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_name' => 'Handyman Service',
                'site_email' => NULL,
                'site_logo' => NULL,
                'site_favicon' => NULL,
                'site_description' => NULL,
                'site_copyright' => '© 2023 All Rights Reserved by <a href="https://iqonic.design" target="_blank">IQONIC Design</a>',
                'facebook_url' => NULL,
                'instagram_url' => NULL,
                'youtube_url' => NULL,
                'twitter_url' => NULL,
                'linkedin_url' => NULL,
                'language_option' => '["nl","fr","it","pt","es","en"]',
                'remember_token' => NULL,
                'inquriy_email' => NULL,
                'helpline_number' => NULL,
            ),
        ));
        
        
    }
}