<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 10)->unique();
            $table->string('domain', 255)->unique();
            $table->string('locale', 10)->default('de');
            $table->string('currency', 3)->default('EUR');
            $table->string('timezone', 50)->default('Europe/Berlin');
            $table->integer('token_price_cents')->default(15);
            $table->text('legal_notice')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('username', 50);
            $table->string('name')->nullable();
            $table->string('email', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 20)->default('user');
            $table->string('avatar_path', 500)->nullable();
            $table->text('bio')->nullable();
            $table->integer('token_balance')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->string('ban_reason', 255)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['tenant_id', 'username']);
            $table->unique(['tenant_id', 'email']);
            $table->index(['tenant_id', 'role']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tenants');
    }
};
