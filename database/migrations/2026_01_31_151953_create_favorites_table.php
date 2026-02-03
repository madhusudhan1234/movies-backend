<?php

use App\Enums\DBTables;
use App\Models\Favorite;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(DBTables::FAVORITES, function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Movie::class, 'movie_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(DBTables::FAVORITES);
    }
};
