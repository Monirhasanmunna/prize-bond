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
        Schema::create('prize_bonds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('bond_series_id');
            $table->string('code')->unique();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('status', 20)->default(STATUS_ACTIVE);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bond_series_id')->references('id')->on('bond_series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_bonds');
    }
};
