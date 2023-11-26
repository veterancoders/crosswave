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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('status', 250);
            $table->string('amount', 500);
            $table->string('currency', 500)->nullable();
            $table->string('payment_method', 250);
            $table->string('paypal_email', 250)->nullable();
            $table->string('eth_address', 250)->nullable();
            $table->string('bank_name', 250)->nullable();
            $table->string('bank_acc_number', 250)->nullable();
            $table->string('bank_acc_name', 250)->nullable();
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
        Schema::dropIfExists('withdrawals');
    }
};
