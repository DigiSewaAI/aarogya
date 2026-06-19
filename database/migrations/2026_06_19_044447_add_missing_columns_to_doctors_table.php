<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add missing columns
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->string('qualification')->nullable()->after('name');
            $table->string('nmc_registration')->unique()->nullable()->after('specialization');
            $table->integer('experience')->nullable()->after('nmc_registration');
            $table->decimal('consultation_fee', 10, 2)->default(0)->after('experience');
            $table->string('profile_photo')->nullable()->after('consultation_fee');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->string('clinic_name')->nullable()->after('bio');
            $table->text('clinic_address')->nullable()->after('clinic_name');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending')->after('clinic_address');
            $table->boolean('is_active')->default(true)->after('verification_status');
            $table->softDeletes()->after('updated_at');

            // Note: phone, address, fee fields already exist in your table
            // If not, add them too:
            // $table->string('phone')->nullable()->after('specialization');
            // $table->text('address')->nullable()->after('phone');
            // $table->decimal('fee', 8, 2)->default(0)->after('address');
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'qualification',
                'nmc_registration',
                'experience',
                'consultation_fee',
                'profile_photo',
                'bio',
                'clinic_name',
                'clinic_address',
                'verification_status',
                'is_active',
                'deleted_at',
            ]);
        });
    }
};