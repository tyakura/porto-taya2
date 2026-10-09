<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            // ERD: CONTACT_MESSAGE { id PK, session_id FK, sender_name, sender_email, message, status, created_at }
            $table->id();
            $table->uuid('session_id')->nullable()->index();  // nullable: bisa kirim form tanpa chat
            $table->string('sender_name', 100);
            $table->string('sender_email', 150);
            $table->text('message');
            $table->string('status', 20)->default('new');     // new | read | replied
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('visitor_events', function (Blueprint $table) {
            // ERD: VISITOR_EVENT { id PK, session_id FK, event_type, target, created_at }
            // event_type contoh: open_chat, close_chat, download_cv, click_contact, send_message
            $table->id();
            $table->uuid('session_id')->nullable()->index();
            $table->string('event_type', 50);
            $table->string('target', 100)->nullable();        // misal: email, whatsapp, cv, github
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_events');
        Schema::dropIfExists('contact_messages');
    }
};
