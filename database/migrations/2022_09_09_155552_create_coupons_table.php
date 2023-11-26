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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 250);
            $table->char('currency_code', 3);
            $table->text('description')->nullable();
            $table->double('discount', 8, 2);
            $table->double('min_amount', 8, 2)->nullable();
            $table->double('max_coupon_amount', 8, 2)->nullable();
            $table->tinyInteger('percentage');
            $table->date('expires_on');
            $table->integer('times')->nullable();
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('coupons');
    }
};
