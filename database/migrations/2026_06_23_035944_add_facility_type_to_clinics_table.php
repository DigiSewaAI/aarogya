<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->enum('facility_type', ['clinic', 'hospital', 'diagnostic', 'other'])
                  ->default('clinic')
                  ->after('clinic_name');
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('facility_type');
        });
    }
};