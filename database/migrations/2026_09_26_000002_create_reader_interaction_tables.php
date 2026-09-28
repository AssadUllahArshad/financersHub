<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->text('name');
            $table->text('email');
            $table->string('subject');
            $table->text('article_url')->nullable();
            $table->longText('message');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->text('email');
            $table->string('email_hash', 64)->unique();
            $table->string('status')->default('pending')->index();
            $table->timestamp('consented_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('provider_id')->nullable()->index();
            $table->string('confirmation_token_hash', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('contact_messages');
    }
};
