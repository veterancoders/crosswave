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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->text('description');
            $table->double('price', 8, 2);
            $table->integer('profit_percent')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->double('signup_fee', 8, 2);
            $table->integer('invoice_period')->nullable();
            $table->string('invoice_interval', 250);
            $table->integer('trial_period')->nullable();
            $table->string('trial_interval', 250)->nullable();
            $table->integer('sort_order')->nullable();
            $table->char('currency', 3)->nullable();
             $table->string('slug', 250)->nullable();
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
        Schema::dropIfExists('plans');
    }
};
