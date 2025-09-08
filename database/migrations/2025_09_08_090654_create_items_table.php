<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
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
            $table->string('element');
            $table->integer('min_defense')->nullable();
            $table->integer('max_defense')->nullable();
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
            $table->integer('transy')->nullable();
            $table->jsonb('spawns');
            $table->timestamps();
        });

        Artisan::call('scraping:items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
