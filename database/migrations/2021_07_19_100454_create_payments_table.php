<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('booking_id');
            $table->dateTime('datetime')->nullable()->default(null);
            $table->double('discount')->nullable()->default('0');
            $table->double('total_amount')->nullable()->default('0');
            $table->string('payment_type', 100);
            $table->string('txn_id', 100)->nullable()->default(null);
            $table->string('payment_status', 20)->nullable()->default(null)->comment('pending, paid , failed');
            $table->text('other_transaction_detail')->nullable()->default(null);

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
