<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesConnectionsTable extends Migration
{
    public function up()
    {
        Schema::create('stages_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_stage_id');
            $table->unsignedBigInteger('child_stage_id');
            $table->timestamps();

            $table->foreign('parent_stage_id')->references('id')->on('parent_stages')->onDelete('cascade');
            $table->foreign('child_stage_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stages_connections');
    }
}

