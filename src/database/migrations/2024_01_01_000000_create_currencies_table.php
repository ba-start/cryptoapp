<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration {
    public function up() {
        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('coin_id')->unique(); // CoinGecko id
            $table->decimal('current_price', 28, 8)->nullable();
            $table->decimal('price_change_percentage_24h', 10, 6)->nullable();
            $table->string('image_url', 1000); // increase limit
            $table->bigInteger('market_cap')->nullable();
            $table->string('symbol')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('currencies');
    }
};
