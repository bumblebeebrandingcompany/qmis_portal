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
        Schema::create('sitevisits', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->date('visit_time');
            $table->string('notes');
            $table->bigInteger('stage_id');
            $table->bigInteger('lead_id');
$table->bigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sitevisits');
    }
};
