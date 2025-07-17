<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent();
            $table->string('level', 20)->default('INFO');
            $table->string('action_type', 50); // e.g., create, update, delete, login, logout
            $table->string('target_type', 50)->nullable(); // e.g., Product, User, Sale
            $table->unsignedBigInteger('target_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('message');
            $table->timestamps();
            $table->index(['action_type', 'target_type', 'target_id']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}; 