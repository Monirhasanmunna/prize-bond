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
        Schema::create('draw_winners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('draw_id');
            $table->string('bond_number');
            $table->string('prize_type');
            $table->double('amount')->default(0);
            $table->timestamps();

            $table->foreign('draw_id')->references('id')->on('draws')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_winners');
    }
};
