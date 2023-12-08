<?php

// database/migrations/xxxx_xx_xx_create_parent_stages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentStagesTable extends Migration
{
    public function up()
    {
        Schema::create('parent_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parent_stages');
    }
}
