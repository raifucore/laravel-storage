<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_objects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('size')->unsigned()->nullable();
            $table->string('storage', 10)->nullable();
            $table->string('type', 25)->nullable();
            $table->string('mime_type')->nullable();
            $table->string('origin_filename')->nullable();
            $table->string('directory')->nullable();
            $table->string('filename')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_objects');
    }
};
