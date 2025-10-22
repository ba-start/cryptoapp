<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchDogsTable extends Migration {
    public function up(): void
    {
        Schema::create('watch_dogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('target_price', 18, 8);
            $table->timestamps();
        });
    }
}