<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new fields for registration
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('username')->unique()->after('last_name');
            $table->enum('role', ['administrator', 'supervisor', 'cashier'])->default('cashier')->after('email');
            $table->boolean('logged_in')->default(false)->after('role');
            
            // Remove the old name field
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn(['first_name', 'last_name', 'username', 'role']);
            
            // Add back the old name field
            $table->string('name');
        });
    }
};
