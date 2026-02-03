<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('service_rendered');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('receipt_no')->nullable();
            $table->decimal('outstanding_balance', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('billings');
    }
};

