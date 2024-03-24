<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->integer('quantity')->nullable()->default('0');
            $table->double('amount')->nullable();
            $table->double('discount')->nullable();
            $table->double('total_amount')->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->bigInteger('coupon_id')->nullable();
            $table->string('status')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('payment_id')->nullable();
            $table->string('duration_diff')->nullable()->default('0');
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('bookings');
    }
}
