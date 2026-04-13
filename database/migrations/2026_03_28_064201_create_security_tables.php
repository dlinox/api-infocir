<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('auth_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('password', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_sign_in_at')->nullable();
            $table->timestamps();

            $table->index('username');
            $table->index('email');
            $table->index('is_active');
        });

        Schema::create('auth_password_resets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reset_token', 255);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth_users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('expires_at');
        });

        Schema::create('auth_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->text('session_token');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth_users')->onDelete('cascade');
            // $table->foreign('core_profile_id')->references('id')->on('core_profiles')->onDelete('cascade');

            $table->index('user_id');
            $table->index('profile_id');
            $table->index('expires_at');
        });

        Schema::create('behavior_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100)->unique();
            $table->string('redirect_to', 255)->nullable();
            // 0: Super Admin
            // 1: Admin
            // 2: undefined
            // 3: undefined
            // 4: undefined
            $table->enum('level', ['0', '1', '2', '3', '4'])->default('1');
            $table->enum('scope', ['admin', 'plant', 'supplier', 'worker', 'instructor'])->default('admin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('level');
            $table->index('scope');
            $table->index('is_active');
        });

        Schema::create('behavior_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('display_name', 100);
            $table->enum('type', ['module', 'menu', 'view', 'action', 'feature'])->default('feature');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('level', ['0', '1', '2', '3', '4'])->default('1');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('behavior_permissions')->onDelete('cascade');
            $table->index('name');
            $table->index('type');
            $table->index('parent_id');
            $table->index('level');
        });

        Schema::create('behavior_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('behavior_roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('behavior_permissions')->onDelete('cascade');
            $table->index('role_id');
            $table->index('permission_id');
            $table->unique(['role_id', 'permission_id'], 'role_perm_unique');
        });

        Schema::create('behavior_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('core_profile_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('auth_users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('behavior_roles')->onDelete('cascade');
            $table->foreign('core_profile_id')->references('id')->on('core_profiles')->onDelete('cascade');

            $table->index('user_id');
            $table->index('core_profile_id');
            $table->index('role_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('behavior_profiles');
        Schema::dropIfExists('behavior_role_permissions');
        Schema::dropIfExists('behavior_permissions');
        Schema::dropIfExists('behavior_roles');

        Schema::dropIfExists('auth_sessions');
        Schema::dropIfExists('auth_password_resets');
        Schema::dropIfExists('auth_users');
    }
};
