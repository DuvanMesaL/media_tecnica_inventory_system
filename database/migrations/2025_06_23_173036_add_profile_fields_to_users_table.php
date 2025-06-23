<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the old role column since we're using Spatie roles
            $table->dropColumn('role');

            // Add new profile fields
            $table->string('first_name')->nullable()->after('name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('second_last_name')->nullable()->after('last_name');
            $table->string('identification_number')->nullable()->unique()->after('second_last_name');
            $table->string('phone_number')->nullable()->after('identification_number');

            // Profile completion tracking
            $table->boolean('profile_completed')->default(false)->after('is_active');
            $table->boolean('password_changed')->default(false)->after('profile_completed');
            $table->timestamp('profile_completed_at')->nullable()->after('password_changed');
            $table->timestamp('last_login_at')->nullable()->after('profile_completed_at');

            // Invitation tracking
            $table->string('invitation_token')->nullable()->after('remember_token');
            $table->timestamp('invitation_sent_at')->nullable()->after('invitation_token');
            $table->timestamp('invitation_accepted_at')->nullable()->after('invitation_sent_at');
            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null')->after('invitation_accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'logistics'])->default('teacher');

            $table->dropForeign(['invited_by']);
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'second_last_name',
                'identification_number',
                'phone_number',
                'profile_completed',
                'password_changed',
                'profile_completed_at',
                'last_login_at',
                'invitation_token',
                'invitation_sent_at',
                'invitation_accepted_at',
                'invited_by'
            ]);
        });
    }
};
