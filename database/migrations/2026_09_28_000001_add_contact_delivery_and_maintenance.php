<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->timestamp('read_at')->nullable();
            $table->string('notification_status')->default('not_configured')->index();
            $table->unsignedInteger('notification_attempts')->default(0);
            $table->timestamp('notification_due_at')->nullable();
            $table->timestamp('notification_claimed_at')->nullable();
            $table->timestamp('notified_at')->nullable();
        });
        Schema::create('maintenance_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('command');
            $table->integer('exit_code')->nullable();
            $table->text('output')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_runs');
        Schema::table('contact_messages', fn (Blueprint $table) => $table->dropColumn(['read_at', 'notification_status', 'notification_attempts', 'notification_due_at', 'notification_claimed_at', 'notified_at']));
    }
};
