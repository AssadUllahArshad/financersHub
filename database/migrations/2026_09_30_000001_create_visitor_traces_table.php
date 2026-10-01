<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('visitor_traces', function (Blueprint $table) {
        $table->id(); $table->char('visitor_hash',64)->index(); $table->char('dedup_key',64)->unique();
        $table->string('path',500); $table->string('referrer_host',255)->nullable();
        $table->string('device',20); $table->string('browser',30); $table->timestamp('visited_at')->index();
    }); }
    public function down(): void { Schema::dropIfExists('visitor_traces'); }
};
