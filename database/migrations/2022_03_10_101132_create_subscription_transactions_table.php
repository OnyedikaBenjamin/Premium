<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('payment_type', 100);
            $table->string('txn_id', 100)->nullable()->default(null);
            $table->string('payment_status', 20)->nullable()->default(null)->comment('pending, paid , failed');
            $table->text('other_transaction_detail')->nullable()->default(null);
            $table->foreign('subscription_plan_id')->references('id')->on('provider_subscriptions')->onDelete('cascade');
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
        Schema::dropIfExists('subscription_transactions');
    }
}
