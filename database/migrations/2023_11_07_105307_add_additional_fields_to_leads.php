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
        Schema::table('leads', function (Blueprint $table) {
            $table->longText('essential_fields')
            ->nullable()
            ->comment('essential fields details');

            $table->longText('sales_fields')
            ->nullable()
            ->comment('sales fields details');

            $table->longText('system_fields')
            ->nullable()
            ->comment('system fields details');
            $table->longText('custom_fields')
            ->nullable()
            ->comment('custom fields details');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
