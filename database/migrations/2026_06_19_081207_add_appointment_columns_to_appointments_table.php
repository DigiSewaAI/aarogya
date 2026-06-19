<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // पहिले नै छ कि छैन check गरौं (ताकी duplicate error नआओस्)
            if (!Schema::hasColumn('appointments', 'patient_id')) {
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('appointments', 'doctor_id')) {
                $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            }
            if (!Schema::hasColumn('appointments', 'appointment_date')) {
                $table->date('appointment_date');
            }
            if (!Schema::hasColumn('appointments', 'appointment_time')) {
                $table->time('appointment_time');
            }
            if (!Schema::hasColumn('appointments', 'symptoms')) {
                $table->text('symptoms')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            }
            // यदि cancelled_at र cancelled_reason पनि चाहिन्छ भने (तिम्रो model मा छ)
            if (!Schema::hasColumn('appointments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'cancelled_reason')) {
                $table->text('cancelled_reason')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // foreign keys drop गर्नु पर्छ
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropColumn([
                'patient_id',
                'doctor_id',
                'appointment_date',
                'appointment_time',
                'symptoms',
                'notes',
                'status',
                'cancelled_at',
                'cancelled_reason',
            ]);
        });
    }
};