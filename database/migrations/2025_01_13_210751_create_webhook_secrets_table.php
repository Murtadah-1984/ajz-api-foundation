<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('webhook_secrets', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('secret');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['identifier', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhook_secrets');
    }
};
