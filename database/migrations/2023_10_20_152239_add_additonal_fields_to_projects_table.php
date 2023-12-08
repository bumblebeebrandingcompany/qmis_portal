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
        Schema::table('projects', function (Blueprint $table) {
            $table->longText('essential_fields')
                ->nullable()
                ->comment('essential fields details')
                ->after('description');
                $table->longText('sales_fields')
                ->nullable()
                ->comment('sales fields details')
                ->after('description');
                $table->longText('system_fields')
                ->nullable()
                ->comment('system fields details')
                ->after('description');



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
