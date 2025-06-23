<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_requested');
            $table->integer('quantity_delivered')->default(0);
            $table->integer('quantity_returned')->default(0);
            $table->enum('condition_delivered', ['good', 'damaged'])->default('good');
            $table->enum('condition_returned', ['good', 'damaged', 'lost'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_loan_items');
    }
};
