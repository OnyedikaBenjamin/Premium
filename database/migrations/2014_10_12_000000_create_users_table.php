<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_type', 255)->nullable();
            $table->string('contact_number', 255)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->text('address')->nullable();
            $table->string('player_id')->nullable();
            $table->tinyInteger('status')->nullable()->default('1');
            $table->string('display_name')->nullable();
            $table->unsignedBigInteger('providertype_id')->nullable();
            $table->tinyInteger('is_featured')->nullable()->default('0');
            $table->string('time_zone')->default('UTC');
            $table->timestamp('last_notification_seen')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
