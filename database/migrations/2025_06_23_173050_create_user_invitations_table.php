<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email', 191)->unique();
            $table->string('token', 191)->unique();
            $table->string('role');
            $table->foreignId('technical_program_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('invited_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->boolean('is_used')->default(false);
            $table->text('metadata')->nullable();
            $table->timestamps();

            // índice simple conservado
            $table->index(['expires_at', 'is_used']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
