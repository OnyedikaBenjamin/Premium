<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('action')->nullable();
            $table->string('text')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->dateTime('datetime')->nullable()->default(null);
            $table->double('total_amount')->nullable()->default('0');
            $table->string('txn_id', 100)->nullable()->default(null);
            $table->text('other_transaction_detail')->nullable()->default(null);
            $table->unsignedBigInteger('parent_id')->nullable();


            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
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
        Schema::dropIfExists('payment_histories');
    }
}
