<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('reader')->index();
        });
        Schema::create('author_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->boolean('is_demo')->default(false);
            $table->timestamps();
        });
        foreach (['categories', 'tags'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('path')->unique();
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('alt_text');
            $table->text('rights');
            $table->timestamps();
        });
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('author_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('media_asset_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('body');
            $table->string('status')->default('draft')->index();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('disclosure')->nullable();
            $table->json('sources')->nullable();
            $table->boolean('is_demo')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->restrictOnDelete();
            $table->primary(['article_id', 'tag_id']);
        });
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('action');
            $table->json('snapshot');
            $table->timestamps();
        });
        Schema::create('faq_entries', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('position')->default(0)->index();
            $table->boolean('published')->default(false)->index();
            $table->timestamps();
        });
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        Schema::create('article_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['article_redirects', 'site_settings', 'faq_entries', 'article_revisions', 'article_tag', 'articles', 'media_assets', 'tags', 'categories', 'author_profiles'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
