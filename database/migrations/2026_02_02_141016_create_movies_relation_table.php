<?php

use App\Enums\DBTables;
use App\Enums\MovieCreditsRole;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\People;
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
        Schema::create(DBTables::MOVIES_GENRE, function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Movie::class);
            $table->foreignIdFor(Genre::class);
            $table->timestamps();
        });

        Schema::create(DBTables::MOVIES_CREDITS, function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Movie::class);
            $table->foreignIdFor(People::class);
            $table->string('role')->default(MovieCreditsRole::ACTOR->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(DBTables::MOVIES_CREDITS);
        Schema::dropIfExists(DBTables::MOVIES_GENRE);
    }
};
