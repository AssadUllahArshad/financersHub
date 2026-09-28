<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faq_entries', fn (Blueprint $table) => $table->string('group')->default('using-financershub')->index());
    }

    public function down(): void
    {
        Schema::table('faq_entries', fn (Blueprint $table) => $table->dropColumn('group'));
    }
};
