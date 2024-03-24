<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostJobRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('post_job_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('post_job_requests', 'description')) {
                $table->text('description')->nullable();
            }
           
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('post_job_requests', function (Blueprint $table) {
            $table->dropColumn('description');
           
        });
    }
}
