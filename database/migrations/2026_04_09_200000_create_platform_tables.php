<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Token Packages
        Schema::create('token_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 50);
            $table->integer('tokens');
            $table->integer('price_cents');
            $table->string('badge_color', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Escort Profiles
        Schema::create('escort_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('display_name', 100);
            $table->string('slug', 100);
            $table->string('city', 100);
            $table->string('neighborhood', 100)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->json('languages')->nullable();
            $table->text('description')->nullable();
            $table->json('services')->nullable();
            $table->json('rates')->nullable();
            $table->json('schedule')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('contact_telegram', 100)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->timestamp('blog_visible_until')->nullable();
            $table->timestamp('featured_until')->nullable();
            $table->timestamp('top_until')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'city']);
            $table->index(['tenant_id', 'is_active', 'featured_until']);
        });

        // Escort Photos
        Schema::create('escort_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escort_profile_id')->constrained()->cascadeOnDelete();
            $table->string('path', 500);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->nullable();
        });

        // Forum Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('type', 20);
            $table->integer('sort_order')->default(0);
            $table->unsignedInteger('threads_count')->default(0);
            $table->unsignedInteger('posts_count')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        // Threads
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('escort_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('body');
            $table->string('type', 20);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_sponsored')->default(false);
            $table->timestamp('sponsored_until')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->timestamp('last_reply_at')->nullable();
            $table->foreignId('last_reply_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'category_id']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'is_sponsored', 'sponsored_until']);
        });

        // Posts
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'thread_id', 'created_at']);
        });

        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('escort_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thread_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->string('title', 255)->nullable();
            $table->text('body');
            $table->date('visit_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->index(['tenant_id', 'escort_profile_id']);
        });

        // Token Transactions
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->integer('amount');
            $table->integer('balance_after');
            $table->string('description', 255)->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->nullable();

            $table->index(['tenant_id', 'user_id', 'created_at']);
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('token_package_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('amount_cents');
            $table->string('currency', 3);
            $table->string('processor', 20);
            $table->string('processor_txn_id', 255)->nullable();
            $table->string('status', 20);
            $table->timestamps();

            $table->index(['tenant_id', 'user_id']);
        });

        // Conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('subject', 255)->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_muted')->default(false);

            $table->unique(['conversation_id', 'user_id']);
        });

        // Messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamp('created_at')->nullable();

            $table->index(['conversation_id', 'created_at']);
        });

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('reportable_type', 50);
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason', 255);
            $table->text('details')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['tenant_id', 'status']);
        });

        // Page Views (analytics)
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('viewable_type', 50);
            $table->unsignedBigInteger('viewable_id');
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 500)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['tenant_id', 'viewable_type', 'viewable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('token_transactions');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('threads');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('escort_photos');
        Schema::dropIfExists('escort_profiles');
        Schema::dropIfExists('token_packages');
    }
};
