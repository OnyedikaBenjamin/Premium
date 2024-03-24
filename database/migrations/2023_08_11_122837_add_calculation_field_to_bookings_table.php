<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalculationFieldToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->double('final_total_service_price')->nullable();
            $table->double('final_total_tax')->nullable();
            $table->double('final_sub_total')->nullable();
            $table->double('final_discount_amount')->nullable();
            $table->double('final_coupon_discount_amount')->nullable();
        });
        $bookings = \App\Models\Booking::all();

        foreach ($bookings as $booking) {

            $booking->final_total_service_price = $booking->getServiceTotalPrice();
            $booking->final_total_tax = $booking->getTaxesValue();
            $booking->final_sub_total = $booking->getSubTotalValue();
            $booking->final_discount_amount = $booking->getDiscountValue();
            $booking->final_coupon_discount_amount = $booking->getCouponDiscountValue();
    
            $booking->save();
            $booking->total_amount = $booking->getTotalValue();
            $booking->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
}
