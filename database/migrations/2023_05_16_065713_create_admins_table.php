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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('domain')->nullable();

            $table->string('profile_logo')->nullable();
            $table->string('renewed_at')->nullable();
            $table->string('expired_at')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('show_password');
            $table->integer('status')->default(0);
            $table->boolean('main_account')->default(0);
            $table->boolean('sound')->default(1);
            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
