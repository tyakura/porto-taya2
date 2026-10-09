<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tanya Taya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ===== PRD §5.3 Neo-brutalist: krem #FFF0E5, hitam #111111, Space Mono ===== */
        :root {
            --cream:  #FFF0E5;
            --black:  #111111;
            --border: 2px solid #111111;
            --radius: 4px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--black);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* ===== WRAPPER ===== */
        .chat-wrapper {
            width: 100%;
            max-width: 420px;
            height: 100vh;
            max-height: 780px;
            display: flex;
            flex-direction: column;
            background: var(--cream);
            border: var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        /* ===== HEADER (PRD §5.2) ===== */
        .chat-header {
            padding: 16px 20px;
            background: var(--black);
            color: var(--cream);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: var(--border);
            flex-shrink: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
        }
        .header-avatar svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: var(--cream);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .header-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .header-subtitle {
            font-size: 10px;
            color: #aaa;
            margin-top: 2px;
            font-weight: 400;
        }

        .close-btn {
            background: none;
            border: 1px solid #444;
            color: var(--cream);
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: var(--radius);
            transition: background 0.15s;
        }

        .close-btn:hover { background: #2a2a2a; }

        /* ===== MESSAGES AREA ===== */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            scroll-behavior: smooth;
        }

        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: #ccc; border-radius: 2px; }

        /* Message bubbles */
        .message {
            display: flex;
            gap: 8px;
            animation: fadeUp 0.25s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(5px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .message.user { flex-direction: row-reverse; }

        .msg-bubble {
            max-width: 78%;
            padding: 10px 14px;
            font-size: 13px;
            line-height: 1.65;
            border: var(--border);
            border-radius: var(--radius);
            word-break: break-word;
            white-space: pre-wrap;
        }

        /* AI bubble: krem bg, border hitam (PRD §5.3) */
        .message.bot  .msg-bubble { background: var(--cream); color: var(--black); }
        /* User bubble: hitam bg, teks krem (PRD §5.3) */
        .message.user .msg-bubble { background: var(--black); color: var(--cream); }

        /* Typing dots (PRD §5.2) */
        .typing-indicator {
            display: none;
            gap: 8px;
            padding: 0 16px 8px;
        }
        .typing-indicator.visible { display: flex; }

        .typing-bubble {
            background: var(--cream);
            border: var(--border);
            border-radius: var(--radius);
            padding: 10px 14px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .typing-bubble span {
            width: 7px;
            height: 7px;
            background: var(--black);
            border-radius: 50%;
            animation: bounce 1.1s infinite;
        }
        .typing-bubble span:nth-child(2) { animation-delay: 0.18s; }
        .typing-bubble span:nth-child(3) { animation-delay: 0.36s; }

        @keyframes bounce {
            0%, 60%, 100% { transform: translateY(0); }
            30%            { transform: translateY(-5px); }
        }

        /* ===== QUICK CHIPS (PRD §5.2) ===== */
        .suggestions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            padding: 0 16px 10px;
        }

        .chip {
            padding: 5px 10px;
            background: var(--cream);
            border: var(--border);
            border-radius: var(--radius);
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .chip:hover {
            background: var(--black);
            color: var(--cream);
        }

        /* ===== INPUT AREA (PRD §5.2) ===== */
        .chat-input-area {
            border-top: var(--border);
            background: var(--cream);
            flex-shrink: 0;
        }

        .input-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            padding: 12px 16px;
            border-bottom: var(--border);
        }

        .message-input {
            flex: 1;
            background: transparent;
            border: var(--border);
            border-radius: var(--radius);
            padding: 8px 10px;
            font-family: 'Space Mono', monospace;
            font-size: 12.5px;
            color: var(--black);
            resize: none;
            max-height: 96px;
            min-height: 38px;
            outline: none;
            line-height: 1.5;
            transition: border-color 0.15s;
        }

        .message-input:focus { border-color: #555; }
        .message-input::placeholder { color: #999; }

        .send-btn {
            background: var(--black);
            color: var(--cream);
            border: var(--border);
            border-radius: var(--radius);
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.15s;
            flex-shrink: 0;
        }

        .send-btn:hover:not(:disabled) { opacity: 0.8; }
        .send-btn:disabled { opacity: 0.35; cursor: not-allowed; }

        .char-count {
            font-size: 10px;
            color: #888;
            text-align: right;
            padding: 2px 16px 0;
        }
        .char-count.warn { color: #c0392b; }

        /* ===== FOOTER (PRD §5.2) ===== */
        .chat-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 16px;
        }

        .footer-note {
            font-size: 10px;
            color: #888;
            flex: 1;
        }

        .reset-btn {
            background: none;
            border: 1px solid #bbb;
            border-radius: var(--radius);
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            color: #666;
            cursor: pointer;
            padding: 3px 8px;
            transition: border-color 0.15s, color 0.15s;
        }

        .reset-btn:hover { border-color: var(--black); color: var(--black); }

        /* ===== OFFLINE BANNER (PRD §6.4) ===== */
        .offline-banner {
            display: none;
            background: #111;
            color: #FFF0E5;
            font-size: 11px;
            text-align: center;
            padding: 6px;
            border-bottom: var(--border);
        }
        .offline-banner.visible { display: block; }

        /* ===== RETRY button inside bubble ===== */
        .retry-btn {
            display: inline-block;
            margin-top: 6px;
            background: none;
            border: 1px solid var(--black);
            border-radius: var(--radius);
            font-family: 'Space Mono', monospace;
            font-size: 11px;
            cursor: pointer;
            padding: 3px 8px;
        }
        .message.user .retry-btn {
            border-color: var(--cream);
            color: var(--cream);
        }

        @media (max-width: 480px) {
            .chat-wrapper { max-height: 100vh; border-radius: 0; border-left: none; border-right: none; }
        }
    </style>
</head>
<body>
<div class="chat-wrapper" role="dialog" aria-modal="true" aria-label="Chat dengan Taya">

    <!-- HEADER -->
    <div class="chat-header">
        <div class="header-left">
            <span class="header-avatar" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
            </span>
            <div>
                <div class="header-title">Tanya Taya</div>
                <div class="header-subtitle">Asisten AI — jawaban berdasarkan profil Attaya</div>
            </div>
        </div>
        <button class="close-btn" onclick="window.close()" aria-label="Tutup panel">[X]</button>
    </div>

    <!-- OFFLINE BANNER -->
    <div class="offline-banner" id="offlineBanner" role="alert">
        Kamu sedang offline. Pesan tersimpan, coba kirim lagi saat koneksi kembali.
    </div>

    <!-- MESSAGES -->
    <div class="chat-messages" id="chatMessages" aria-live="polite" aria-atomic="false">
        <div class="message bot">
            <div class="msg-bubble">
                Hai! Aku asisten AI Taya. Tanya apa saja soal skill, proyek, atau cara menghubunginya.
            </div>
        </div>
    </div>

    <!-- TYPING INDICATOR -->
    <div class="typing-indicator" id="typingIndicator" aria-live="polite" aria-label="Taya sedang mengetik">
        <div class="typing-bubble">
            <span></span><span></span><span></span>
        </div>
    </div>

    <!-- QUICK CHIPS -->
    <div class="suggestions" id="suggestions">
        <button class="chip" onclick="sendChip(this)">Apa skill utamanya?</button>
        <button class="chip" onclick="sendChip(this)">Lihat proyeknya</button>
        <button class="chip" onclick="sendChip(this)">Cara menghubungi</button>
        <button class="chip" onclick="sendChip(this)">Download CV</button>
    </div>

    <!-- INPUT AREA -->
    <div class="chat-input-area">
        <div class="input-row">
            <textarea
                id="messageInput"
                class="message-input"
                placeholder="Ketik pertanyaan... (maks 500 karakter)"
                rows="1"
                maxlength="500"
                aria-label="Tulis pesan"
            ></textarea>
            <button class="send-btn" id="sendBtn" onclick="sendMessage()" aria-label="Kirim pesan">
                KIRIM ↵
            </button>
        </div>
        <div class="char-count" id="charCount">0 / 500</div>
        <div class="chat-footer">
            <span class="footer-note">Jawaban AI bisa keliru. Cek email untuk konfirmasi.</span>
            <button class="reset-btn" onclick="resetChat()">Reset percakapan</button>
        </div>
    </div>
</div>

<script>
const chatMessages  = document.getElementById('chatMessages');
const messageInput  = document.getElementById('messageInput');
const sendBtn       = document.getElementById('sendBtn');
const typingIndicator = document.getElementById('typingIndicator');
const suggestions   = document.getElementById('suggestions');
const charCount     = document.getElementById('charCount');
const offlineBanner = document.getElementById('offlineBanner');

let history = [];
let pendingMessage = null; // keep unsent message when offline (PRD §6.4)

// ── Offline detection ──────────────────────────────────────────
window.addEventListener('online',  () => {
    offlineBanner.classList.remove('visible');
    if (pendingMessage) { messageInput.value = pendingMessage; pendingMessage = null; }
});
window.addEventListener('offline', () => offlineBanner.classList.add('visible'));

// ── Textarea auto-resize + char counter ───────────────────────
messageInput.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 96) + 'px';
    const len = this.value.length;
    charCount.textContent = `${len} / 500`;
    charCount.classList.toggle('warn', len > 450);
});

// ── Enter to send, Shift+Enter newline (PRD §5.2) ─────────────
messageInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// ── Quick chips ───────────────────────────────────────────────
function sendChip(el) {
    messageInput.value = el.textContent;
    suggestions.style.display = 'none';
    sendMessage();
}

// ── Append bubble ─────────────────────────────────────────────
function appendMessage(role, text, retryable) {
    const row = document.createElement('div');
    row.className = `message ${role}`;

    const bubble = document.createElement('div');
    bubble.className = 'msg-bubble';
    bubble.textContent = text;

    if (retryable) {
        const btn = document.createElement('button');
        btn.className = 'retry-btn';
        btn.textContent = '↺ Coba lagi';
        btn.onclick = () => {
            row.remove();
            // re-use last user message from history
            const lastUser = [...history].reverse().find(m => m.role === 'user');
            if (lastUser) { messageInput.value = lastUser.content; sendMessage(); }
        };
        bubble.appendChild(document.createElement('br'));
        bubble.appendChild(btn);
    }

    row.appendChild(bubble);
    chatMessages.appendChild(row);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return bubble;
}

// ── Loading state ─────────────────────────────────────────────
function setLoading(on) {
    sendBtn.disabled = on;
    typingIndicator.classList.toggle('visible', on);
    if (on) chatMessages.scrollTop = chatMessages.scrollHeight;
}

// ── Main send ─────────────────────────────────────────────────
async function sendMessage() {
    const text = messageInput.value.trim();
    if (!text || sendBtn.disabled) return;

    if (!navigator.onLine) {
        pendingMessage = text;
        offlineBanner.classList.add('visible');
        return;
    }

    suggestions.style.display = 'none';
    appendMessage('user', text);
    history.push({ role: 'user', content: text });

    messageInput.value = '';
    messageInput.style.height = 'auto';
    charCount.textContent = '0 / 500';
    setLoading(true);

    try {
        const res = await fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                'Accept':        'application/json',
            },
            body: JSON.stringify({ message: text, history }),
        });

        const data = await res.json();

        if (res.status === 429) {
            appendMessage('bot', data.error ?? 'Terlalu banyak pesan, coba lagi sebentar.', false);
        } else if (!res.ok || data.error) {
            appendMessage('bot', data.error ?? 'Terjadi kesalahan. Coba lagi ya.', data.retryable ?? false);
        } else {
            appendMessage('bot', data.reply);
            history.push({ role: 'assistant', content: data.reply });
        }
    } catch (err) {
        // Network error
        pendingMessage = text;
        appendMessage('bot', 'Koneksi terputus. Pesan kamu tersimpan — coba kirim lagi saat online.', false);
    } finally {
        setLoading(false);
    }
}

// ── Reset (PRD §5.2 footer) ───────────────────────────────────
function resetChat() {
    if (!confirm('Reset percakapan? Riwayat chat akan hilang.')) return;
    history = [];
    chatMessages.innerHTML = `
        <div class="message bot">
            <div class="msg-bubble">
                Hai! Aku asisten AI Taya. Tanya apa saja soal skill, proyek, atau cara menghubunginya.
            </div>
        </div>`;
    suggestions.style.display = 'flex';
}

// ── Focus input on load ───────────────────────────────────────
messageInput.focus();
</script>
</body>
</html>
