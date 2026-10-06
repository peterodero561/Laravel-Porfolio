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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 500);
            $table->longText('full_description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('category', 32);
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->date('project_date')->nullable();

            // Case-study content
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->json('features')->nullable();
            $table->text('architecture')->nullable();
            $table->text('technical_implementation')->nullable();
            $table->text('challenges')->nullable();
            $table->text('results')->nullable();

            $table->timestamps();

            // Query-pattern indexes
            $table->index(['published', 'featured', 'sort_order'], 'projects_pub_feat_sort_idx');
            $table->index(['published', 'category', 'sort_order'], 'projects_pub_cat_sort_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
