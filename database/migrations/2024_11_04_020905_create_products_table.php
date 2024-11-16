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
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->index();
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('name')->nullable();
            $table->string('product_code')->unique();
            $table->string('serial_number')->nullable()->unique();
            $table->integer('parent_product_id')->default(0);
            $table->tinyInteger('status')->default(0)->comment("0: available, 1: assigned, 2: repairing, 3: retired");
            $table->text('description')->nullable();
            $table->dateTime('purchased_date');
            $table->json('attribute_value')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
