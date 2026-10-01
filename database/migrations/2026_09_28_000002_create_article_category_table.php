<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->primary(['article_id', 'category_id']);
        });
        DB::table('articles')->orderBy('id')->chunkById(100, function ($articles) {
            foreach ($articles as $article) {
                DB::table('article_category')->insert(['article_id' => $article->id, 'category_id' => $article->category_id]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category');
    }
};
