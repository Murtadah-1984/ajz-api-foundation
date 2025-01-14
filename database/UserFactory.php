<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => '+' . fake()->numberBetween(1, 9) . fake()->numerify('#########'),
            'email_verified_at' => null,
            'email_verification_token' => Str::random(32),
            'password' => Hash::make('X9k#mP2$vL5nQ8'), // Use the same secure password from tests
            'remember_token' => Str::random(10),
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
        ];
    }

    /**
     * Indicate that the user's email is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }

    /**
     * Indicate that the user has a pending OTP.
     */
    public function withPendingOtp(): static
    {
        return $this->state(fn (array $attributes) => [
            'otp_code' => (string) fake()->numberBetween(100000, 999999),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
        ]);
    }

    /**
     * Indicate that the user has expired OTP.
     */
    public function withExpiredOtp(): static
    {
        return $this->state(fn (array $attributes) => [
            'otp_code' => (string) fake()->numberBetween(100000, 999999),
            'otp_expires_at' => now()->subMinutes(10),
            'otp_attempts' => 0,
        ]);
    }

    /**
     * Indicate that the user has maximum OTP attempts.
     */
    public function withMaxOtpAttempts(): static
    {
        return $this->state(fn (array $attributes) => [
            'otp_code' => (string) fake()->numberBetween(100000, 999999),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 3,
        ]);
    }
}
