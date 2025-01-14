<?php

declare(strict_types=1);

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->unique()->after('email');
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            $table->string('otp_code', 6)->nullable()->after('email_verification_token');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->unsignedTinyInteger('otp_attempts')->default(0)->after('otp_expires_at');
            $table->softDeletes();

            // Add indexes for performance
            $table->index('email_verification_token');
            $table->index('otp_code');
            $table->index(['otp_code', 'otp_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone_number',
                'email_verification_token',
                'otp_code',
                'otp_expires_at',
                'otp_attempts',
            ]);
            $table->dropIndex(['email_verification_token']);
            $table->dropIndex(['otp_code']);
            $table->dropIndex(['otp_code', 'otp_expires_at']);
        });
    }
};
