<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration creates the OAuth 2.0 tables required by Laravel Passport
     * with added company_id support for multi-tenancy (Akaunting structure).
     *
     * @return void
     */
    public function up()
    {
        // OAuth Clients Table
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('secret', 100)->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client')->default(0);
            $table->boolean('password_client')->default(0);
            $table->boolean('revoked')->default(0);
            $table->string('registration_token', 64)->nullable(); // Adds `registration_token` to oauth_clients to support RFC 7591/7592        
            $table->timestamp('registration_token_expires_at')->nullable(); // RFC 7591 registration token expiration 
            $table->boolean('is_public')->default(false); // true = public (PKCE, no secret), false = confidential (has secret)
            $table->json('grant_types')->nullable(); // RFC 7591 grant types (stored as JSON array)
            $table->json('response_types')->nullable(); // RFC 7591 response types (stored as JSON array)
            $table->text('scopes')->nullable(); // RFC 7591 scope (space-separated string per RFC 6749)
            $table->string('client_uri', 2048)->nullable(); // RFC 7591 Client Metadata - URLs
            $table->string('logo_uri', 2048)->nullable(); // RFC 7591 Client Metadata - URLs
            $table->string('tos_uri', 2048)->nullable(); // RFC 7591 Client Metadata - URLs
            $table->string('policy_uri', 2048)->nullable(); // RFC 7591 Client Metadata - URLs
            $table->json('contacts')->nullable(); // RFC 7591 contacts (stored as JSON array of email strings)
            $table->string('created_from')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'user_id']);
        });

        // OAuth Personal Access Clients Table
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('client_id');
            $table->string('created_from')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'client_id']);
        });

        // OAuth Access Tokens Table
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('client_id');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->string('audience')->nullable()->index();
            $table->boolean('revoked')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->string('created_from')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'user_id']);
            $table->index(['company_id', 'client_id']);
        });

        // OAuth Refresh Tokens Table
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('access_token_id', 100)->index();
            $table->string('audience')->nullable()->index();
            $table->boolean('revoked')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->string('created_from')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'access_token_id']);
        });

        // OAuth Auth Codes Table
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('client_id');
            $table->text('scopes')->nullable();
            $table->string('audience')->nullable()->index();
            $table->boolean('revoked')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->string('created_from')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'user_id']);
            $table->index(['company_id', 'client_id']);
        });

        Schema::create('oauth_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type', 50)->index(); // token.created, token.revoked, client.created, etc.
            $table->string('resource_type', 50)->nullable(); // token, client, scope
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_id', 100)->nullable()->index();
            $table->string('token_id', 100)->nullable()->index();
            $table->json('scopes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Extra data (expires_at, grant_type, etc.)
            $table->timestamp('created_at')->useCurrent();

            // Indexes for common queries
            $table->index(['company_id', 'event_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['client_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
        Schema::dropIfExists('oauth_personal_access_clients');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_activity_logs');
    }
};
