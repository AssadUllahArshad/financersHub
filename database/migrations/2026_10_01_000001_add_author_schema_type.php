<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::table('author_profiles',fn(Blueprint $table)=>$table->string('schema_type',20)->default('Person'));}
 public function down(): void {Schema::table('author_profiles',fn(Blueprint $table)=>$table->dropColumn('schema_type'));}
};
