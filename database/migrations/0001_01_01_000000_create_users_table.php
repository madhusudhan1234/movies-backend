<?php

use App\Enums\DBTables;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(DBTables::USERS, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default(UserRole::USER->value);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create(DBTables::PASSWORD_RESET_TOKENS, function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(DBTables::PASSWORD_RESET_TOKENS);
        Schema::dropIfExists(DBTables::USERS);
    }
};
