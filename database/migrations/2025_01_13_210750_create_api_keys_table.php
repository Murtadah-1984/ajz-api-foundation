<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key', 32)->unique();
            $table->string('secret')->nullable();
            $table->string('tier')->default('bronze');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            // Indexes for better performance
            $table->index(['key', 'is_active', 'expires_at']);
            $table->index('tier');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
            $table->index('created_by');

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
};
