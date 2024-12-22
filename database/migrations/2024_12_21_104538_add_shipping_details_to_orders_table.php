<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('shipping_name');
        $table->string('shipping_address');
        $table->string('shipping_city');
        $table->string('shipping_state');
        $table->string('shipping_zip');
        $table->string('shipping_phone');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'shipping_name',
            'shipping_address',
            'shipping_city',
            'shipping_state',
            'shipping_zip',
            'shipping_phone'
        ]);
    });
}
};
