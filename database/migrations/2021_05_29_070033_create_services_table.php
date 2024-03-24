<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('provider_id');
            $table->double('price')->nullable()->default('0');
            $table->string('type')->nullable()->default()->comment('fixed , hourly');
            $table->string('duration')->nullable();
            $table->double('discount')->nullable()->comment('in percentage');
            $table->tinyInteger('status')->nullable()->default('1');
            $table->text('description')->nullable();
            $table->tinyInteger('is_featured')->nullable()->default('0');
            $table->bigInteger('added_by')->nullable();
                        
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('services');
    }
}
