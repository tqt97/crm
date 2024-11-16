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
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->index();
            $table->unsignedInteger('category_attribute_id');
            $table->foreign('category_attribute_id')->references('id')->on('category_attributes');
            $table->string('value', 255);
            $table->timestamps();
            $table->unique(['category_attribute_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_options');

    }
};
