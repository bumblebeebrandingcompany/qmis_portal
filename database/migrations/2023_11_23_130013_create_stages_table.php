<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_stage_id');
            $table->json('selected_child_stages');
            $table->timestamps();

            $table->foreign('parent_stage_id')->references('id')->on('parent_stages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stages');
    }
}
