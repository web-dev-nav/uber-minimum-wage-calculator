<?php

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
        Schema::create('province_wage_rates', function (Blueprint $table) {
            $table->id();
            $table->string('province_code', 2);
            $table->string('province_name');
            $table->decimal('minimum_wage', 8, 2);
            $table->decimal('digital_platform_wage', 8, 2)->nullable();
            $table->date('effective_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['province_code', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province_wage_rates');
    }
};
