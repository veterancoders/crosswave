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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 10, 2);
            $table->string('currency_code', 100)->nullable();
            $table->string('ref', 250)->nullable();
            $table->string('reason', 250)->nullable();
            $table->string('session_id', 250)->nullable();
            $table->bigInteger('wallet_id');
            $table->integer('payment_method_id')->nullable();
            $table->string('status', 250);
            $table->tinyInteger('is_credit')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
