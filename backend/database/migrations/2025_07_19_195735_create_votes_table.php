<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemilih_id');
            $table->unsignedBigInteger('kandidat_id');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('voted_at');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pemilih_id')->references('id')->on('pemilih')->onDelete('cascade');
            $table->foreign('kandidat_id')->references('id')->on('kandidat')->onDelete('cascade');
            
            // Ensure one vote per voter
            $table->unique('pemilih_id');
            
            // Index for performance
            $table->index(['kandidat_id', 'voted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
