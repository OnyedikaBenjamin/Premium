<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHandymanPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handyman_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('handyman_id')->nullable();
            $table->text('payment_method')->nullable();
            $table->text('description')->nullable();
            $table->double('amount')->nullable();
            $table->dateTime('paid_date')->nullable();
            $table->foreign('handyman_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('handyman_payouts');
    }
}
