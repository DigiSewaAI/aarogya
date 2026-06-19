<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();  // <-- nullable
            $table->string('phone')->nullable();           // <-- nullable
            $table->string('nmc_registration')->unique()->nullable();
            $table->integer('experience')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('clinic_name')->nullable();
            $table->text('clinic_address')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};