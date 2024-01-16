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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->integer('user_document');
            $table->integer('monto');
            $table->dateTime('payment_date');
            $table->dateTime('deadline_date');
            $table->string('id_payment')->nullable();
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign('user_document')->references('document')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
