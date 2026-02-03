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
        Schema::create('player_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('class_id')->unique();
            $table->jsonb('name');
            $table->string('type')->nullable();
            $table->string('tree');
            $table->unsignedBigInteger('parent_class_id')->nullable();
            $table->string('icon');
            $table->integer('min_level');
            $table->integer('max_level');
            $table->decimal('hp', 8, 4);
            $table->string('max_hp');
            $table->decimal('fp', 8, 4);
            $table->string('max_fp');
            $table->decimal('mp', 8, 4);
            $table->string('max_mp');
            $table->decimal('defense', 8, 4);
            $table->decimal('magic_defense_sta_factor', 8, 4);
            $table->decimal('magic_defense_int_factor', 8, 4);
            $table->integer('attack_speed');
            $table->decimal('block', 8, 4);
            $table->integer('critical');
            $table->jsonb('auto_attack_factors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_classes');
    }
};
