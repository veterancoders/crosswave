<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('amount', 250);
            $table->integer('plan_id');
            $table->integer('status');
             $table->integer('reinvest_limit')->nullable();
            $table->tinyInteger('can_reinvest')->nullable();
            $table->string('payment_prove', 250)->nullable();
            $table->string('payout_amount', 250)->nullable();
            $table->string('currency', 250)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('payout_date')->nullable();
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
        Schema::dropIfExists('investments');
    }
};
