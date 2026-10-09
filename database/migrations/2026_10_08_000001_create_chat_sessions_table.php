<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            // ERD: CHAT_SESSION { uuid id PK, anon_visitor_id, ip_hash, language, device_type, started_at, last_active_at }
            $table->uuid('id')->primary();
            $table->string('anon_visitor_id', 64)->nullable()->index();
            $table->string('ip_hash', 64)->nullable();
            $table->string('language', 10)->default('id');
            $table->string('device_type', 20)->default('unknown'); // desktop / mobile / tablet
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('last_active_at')->useCurrent();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            // ERD: CHAT_MESSAGE { id PK, session_id FK, knowledge_doc_id FK, role, content, model,
            //                     tokens_in, tokens_out, latency_ms, was_unanswered, created_at }
            $table->id();
            $table->uuid('session_id')->index();
            $table->unsignedInteger('knowledge_doc_id')->nullable(); // untuk versi future
            $table->string('role', 20);           // user | assistant
            $table->text('content');
            $table->string('model', 80)->nullable();
            $table->unsignedInteger('tokens_in')->default(0);
            $table->unsignedInteger('tokens_out')->default(0);
            $table->unsignedInteger('latency_ms')->default(0);
            $table->boolean('was_unanswered')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('session_id')
                  ->references('id')
                  ->on('chat_sessions')
                  ->onDelete('cascade');
        });

        Schema::create('feedback', function (Blueprint $table) {
            // ERD: FEEDBACK { id PK, session_id FK, message_id, rating, comment, created_at }
            $table->id();
            $table->uuid('session_id')->index();
            $table->unsignedBigInteger('message_id')->nullable();
            $table->string('rating', 10);          // thumbs_up | thumbs_down
            $table->string('comment', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('session_id')
                  ->references('id')
                  ->on('chat_sessions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
