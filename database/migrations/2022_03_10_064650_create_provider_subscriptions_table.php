<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('title');
            $table->string('identifier');
            $table->string('type');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->double('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_id')->nullable();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
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
        Schema::dropIfExists('provider_subscriptions');
    }
}
