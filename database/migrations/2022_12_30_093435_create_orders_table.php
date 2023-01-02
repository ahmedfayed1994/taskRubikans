<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->bigInteger('store_id');
            $table->bigInteger('customer_id');
            $table->float('total_price')->nullable();
            $table->float('discount_price')->nullable();
            $table->integer('vat')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->string('order_receiver_name')->nullable();
            $table->string('order_receiver_address')->nullable();
            $table->decimal('order_total_before_tax')->nullable();
            $table->decimal('order_total_tax')->nullable();
            $table->decimal('order_amount_paid')->nullable();
            $table->decimal('order_total_amount_due')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
