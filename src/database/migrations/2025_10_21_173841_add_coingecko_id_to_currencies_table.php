<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('currencies', function ($table) {
        $table->string('coingecko_id')->nullable()->after('symbol');
    });
}

public function down()
{
    Schema::table('currencies', function ($table) {
        $table->dropColumn('coingecko_id');
    });
}

};
