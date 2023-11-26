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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('deposit_type', 250)->nullable();
            $table->string('refrence', 250)->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('status', 250)->nullable();
            $table->string('payment_proof', 250)->nullable();
            $table->tinyInteger('has_payment_proof', 250)->nullable();
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
        Schema::dropIfExists('deposits');
    }
};
