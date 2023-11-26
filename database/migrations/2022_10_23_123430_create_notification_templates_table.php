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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)->nullable();
            $table->text('subject', 250)->nullable();
            $table->text('help', 250)->nullable();
            $table->string('greeting', 250)->nullable();
            $table->text('thanks', 250)->nullable();
            $table->text('sms_body', 250)->nullable();
            $table->text('slack_body', 250)->nullable();
            $table->text('email_body', 250)->nullable();
            $table->string('action_url', 250)->nullable();
            $table->string('action_text', 250)->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('database_body', 250)->nullable();
            $table->text('enabled_channels', 250)->nullable();
            $table->string('status', 250)->nullable();
            $table->string('sms_recipient', 250)->nullable();
            $table->string('default_sms_gateway', 250)->nullable();
            $table->string('webpush_body', 250)->nullable();

            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('notification_templates');
    }
};
