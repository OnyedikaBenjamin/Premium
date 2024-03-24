<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageOptionToAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->text('language_option')->nullable();
        });
        
        // Insert some stuff
        $rows = DB::table('app_settings')->get(['id','language_option']);
        foreach ($rows as $row) {
            DB::table('app_settings')
                ->where('id', $row->id)
                ->update(['language_option' => '["nl","fr","it","pt","es","en"]' ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
