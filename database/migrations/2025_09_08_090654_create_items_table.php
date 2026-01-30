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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('item_id')->index();
            $table->jsonb('name');
            $table->jsonb('description');
            $table->string('icon');
            $table->integer('class')->nullable();
            $table->integer('level');
            $table->string('element')->nullable();
            $table->integer('min_defense')->nullable();
            $table->integer('max_defense')->nullable();
            $table->integer('min_attack')->nullable();
            $table->integer('max_attack')->nullable();
            $table->string('attack_speed')->nullable();
            $table->float('attack_speed_value')->nullable();
            $table->integer('attack_range')->nullable();
            $table->boolean('two_handed')->nullable();
            $table->integer('additional_skill_damage')->nullable();
            $table->boolean('ultimate_convertible')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->string('rarity');
            $table->string('sex')->nullable();
            $table->integer('stack');
            $table->integer('buy_price')->nullable();
            $table->integer('sell_price');
            $table->boolean('consumable');
            $table->boolean('premium');
            $table->boolean('shining');
            $table->boolean('tradable');
            $table->boolean('deletable');
            $table->boolean('duration_real_time');
            $table->integer('duration')->nullable();
            $table->integer('transy')->nullable();
            $table->jsonb('spawns');
            $table->jsonb('abilities')->nullable();
            $table->jsonb('trigger_skill')->nullable();
            $table->integer('trigger_skill_probability')->nullable();
            $table->integer('consumed_mp')->nullable();
            $table->string('consumed_item')->nullable();
            $table->float('cooldown')->nullable();
            $table->float('casting')->nullable();
            $table->jsonb('contents')->nullable();
            $table->jsonb('dismantle')->nullable();
            $table->jsonb('possible_random_stats')->nullable();
            $table->integer('element_attack')->nullable();
            $table->integer('flight_speed')->nullable();
            $table->integer('guild_contribution')->nullable();
            $table->jsonb('location')->nullable();
            $table->integer('minimum_target_item_level')->nullable();
            $table->jsonb('blinkwing_target')->nullable();
            $table->integer('couple_bank_slots')->nullable();
            $table->integer('couple_cheers')->nullable();
            $table->integer('couple_teleports')->nullable();
            $table->integer('fishing_large_chance')->nullable();
            $table->integer('gathering_chance')->nullable();
            $table->jsonb('upgrade_levels')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
