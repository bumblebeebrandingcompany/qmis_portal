<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallLogsTable extends Migration
{
    public function up()
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('calldate');
            $table->string('source');
            $table->string('destination');
            $table->integer('call_duration_with_ringing');
            $table->integer('call_duration_talk_time');
            $table->string('call_disposition');
            $table->string('recording_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('call_logs');
    }
}

