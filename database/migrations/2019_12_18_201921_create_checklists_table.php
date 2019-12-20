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
            $table->string('type',40)->nullable();
            $table->string('object_domain')->nullable();
            $table->string('object_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_complete')->nullable();
            $table->date('completed_at')->nullable();  
            $table->integer('created_by')->nullable(); 
            $table->integer('updated_by')->nullable(); 
            $table->string('due')->nullable();
            $table->integer('due_interval')->nullable();
            $table->string('due_unit')->nullable();
            $table->integer('urgency')->nullable();
            $table->integer('template_id');
            $table->integer('pos')->nullable();
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
