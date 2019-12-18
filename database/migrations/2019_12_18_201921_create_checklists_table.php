<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',40);
            $table->string('attr_object_domain');
            $table->string('attr_object_id');
            $table->text('attr_description');
            $table->boolean('attr_is_complete');
            $table->dateTime('attr_completed_at', 0);  
            $table->integer('attr_updated_by'); 
            $table->string('attr_due');
            $table->integer('attr_urgency');
            $table->integer('template_id');
            $table->integer('pos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
