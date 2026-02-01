<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('imdb_id')->unique()->nullable();
            $table->string('title');
            $table->integer('year')->nullable();
            $table->string('rated')->nullable();
            $table->date('released')->nullable();
            $table->string('runtime')->nullable();
            $table->string('genre')->nullable();
            $table->string('director')->nullable();
            $table->string('writer')->nullable();
            $table->string('actors')->nullable();
            $table->text('plot')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();
            $table->string('awards')->nullable();
            $table->string('poster')->nullable();
            $table->decimal('metascore', 5, 2)->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();
            $table->string('imdb_votes')->nullable();
            $table->string('type')->nullable();
            $table->date('dvd')->nullable();
            $table->string('box_office')->nullable();
            $table->string('production')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
