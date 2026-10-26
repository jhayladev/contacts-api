<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('phone_number', 255);
            $table->string('address', 255);
            $table->string('email', 255);
            $table->foreignId('created_by')->constrained('contacts');
            $table->foreignId('updated_by')->constrained('contacts');
            $table->foreignId('deleted_by')->nullable()->constrained('contacts');
            $table->string('url_avatar', 255);
            $table->timestamps();

            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
