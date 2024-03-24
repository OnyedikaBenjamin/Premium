<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVotesToProviderPayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_payouts', function (Blueprint $table) {
            // Check if the 'status' and 'bank_id' columns do not exist before adding them
            if (!Schema::hasColumn('provider_payouts', 'status')) {
                $table->text('status')->nullable();
            }
            
            if (!Schema::hasColumn('provider_payouts', 'bank_id')) {
                $table->integer('bank_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_payouts', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('bank_id');
        });
    }
}
