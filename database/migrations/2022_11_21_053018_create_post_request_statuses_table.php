<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostRequestStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_request_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('value')->nullable();
            $table->string('label')->nullable();
            $table->tinyInteger('status')->nullable()->default('1')->comment('0-inactive , 1 - active');
            $table->integer('sequence')->nullable()->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_request_statuses');
    }
}
