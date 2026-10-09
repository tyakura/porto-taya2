/**
 * widget.js — Chatbot "Tangan Melambai" untuk portofolio Attaya Arkarna
 * PRD versi 2.0 — F1 (tombol tangan) + F2 (panel fade-in) + F3 (backend Groq)
 *
 * CARA PAKAI:
 *   Tambahkan di portofolio kamu sebelum </body>:
 *     <script src="https://your-backend.com/widget.js"></script>
 */

(function () {
  'use strict';

  // ── CONFIG ──────────────────────────────────────────────────────────────
  const BACKEND_BASE      = '';
  const CHAT_ENDPOINT     = BACKEND_BASE + '/api/chat';
  const FEEDBACK_ENDPOINT = BACKEND_BASE + '/api/chat/feedback';
  const EVENT_ENDPOINT    = BACKEND_BASE + '/api/chat/event';

  const CSRF_TOKEN = (function () {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  })();

  const SIDEBAR_SELECTOR = '.sidebar-social, .social-links, [data-social-sidebar]';

  // ── SVG icons (inline, stroke-based, Lucide-style) ─────────────────────
  // Dipakai di seluruh widget — tidak ada emoji sama sekali
  const SVG = {
    // Tombol tangan melambai → pakai ikon "thumbs-up" sebagai penanda sapaan
    hand: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
             <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
             <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
           </svg>`,

    // Tutup panel
    close: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>`,

    // Kirim pesan
    send: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
             <line x1="22" y1="2" x2="11" y2="13"/>
             <polygon points="22 2 15 22 11 13 2 9 22 2"/>
           </svg>`,

    // Offline / peringatan
    warning: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>`,

    // Feedback: like
    thumbUp: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
              </svg>`,

    // Feedback: dislike
    thumbDown: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"/>
                  <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                </svg>`,

    // Retry / ulang
    retry: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="1 4 1 10 7 10"/>
              <path d="M3.51 15a9 9 0 1 0 .49-4.68"/>
            </svg>`,
  };

  // ── Inject CSS ──────────────────────────────────────────────────────────
  const css = `
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap');

    :root {
      --tw-cream:  #FFF0E5;
      --tw-black:  #111111;
      --tw-border: 2px solid #111111;
      --tw-radius: 4px;
    }

    /* ── HAND BUTTON (PRD §4) ── */
    .taya-hand-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      background: transparent;
      border: 1px solid #555;
      border-radius: 4px;
      cursor: pointer;
      position: relative;
      transition: background 0.2s, border-color 0.2s;
      outline: none;
      color: var(--tw-cream);
    }

    .taya-hand-btn svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
    }

    /* Active: latar putih-krem, ikon hitam (PRD §4.3) */
    .taya-hand-btn[aria-expanded="true"] {
      background: var(--tw-cream);
      border-color: var(--tw-cream);
      color: var(--tw-black);
    }

    .taya-hand-btn:hover,
    .taya-hand-btn:focus-visible {
      background: #2a2a2a;
      border-color: #888;
    }

    /* Tooltip (PRD §4.1) */
    .taya-hand-btn::after {
      content: 'Tanya aku';
      position: absolute;
      left: calc(100% + 10px);
      top: 50%;
      transform: translateY(-50%);
      background: var(--tw-black);
      color: var(--tw-cream);
      font-family: 'Space Mono', monospace;
      font-size: 10px;
      padding: 4px 8px;
      border-radius: 3px;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.2s;
      z-index: 10000;
      border: 1px solid #444;
    }
    .taya-hand-btn:hover::after,
    .taya-hand-btn:focus-visible::after { opacity: 1; }

    /* ── WAVE ANIMATION pada ikon tangan (PRD §4.2) ── */
    .taya-hand-btn .taya-hand-icon {
      display: inline-flex;
      transform-origin: 70% 90%;
    }

    .taya-hand-btn:hover .taya-hand-icon,
    .taya-hand-btn:focus-visible .taya-hand-icon {
      animation: taya-wave 0.9s ease-in-out infinite;
    }

    .taya-hand-icon.taya-auto-wave {
      animation: taya-wave 0.9s ease-in-out 2;
    }

    @keyframes taya-wave {
      0%,  100% { transform: rotate(0deg); }
      20%        { transform: rotate(18deg); }
      40%        { transform: rotate(-12deg); }
      60%        { transform: rotate(18deg); }
      80%        { transform: rotate(-8deg); }
    }

    /* Reduced motion (PRD §4.2) */
    @media (prefers-reduced-motion: reduce) {
      .taya-hand-btn:hover .taya-hand-icon,
      .taya-hand-icon.taya-auto-wave { animation: none; }
      .taya-hand-btn:hover { box-shadow: 0 0 0 2px var(--tw-cream); }
    }

    /* ── OVERLAY (klik luar untuk tutup) ── */
    .taya-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9998;
      background: transparent;
    }
    .taya-overlay.open { display: block; }

    /* ── PANEL — posisi kanan sidebar, tidak menutupi navbar (PRD §5.1 steering fix) ──
       Panel diposisikan fixed di sebelah kanan sidebar (left: 60px).
       top: 0, height: 100vh sehingga tidak bergeser konten.
       Animasi: FADE-IN (opacity + scale kecil), bukan slide-in.              */
    .taya-panel {
      position: fixed;
      top: 0;
      /* Mulai tepat di sebelah kanan sidebar (56px lebar sidebar + 4px jarak) */
      left: 60px;
      width: 380px;
      max-width: calc(100vw - 64px);
      height: 100vh;
      background: var(--tw-cream);
      border-right: var(--tw-border);
      border-left: var(--tw-border);
      z-index: 9999;
      display: flex;
      flex-direction: column;
      font-family: 'Space Mono', monospace;

      /* ── FADE-IN state: tersembunyi ── */
      opacity: 0;
      pointer-events: none;
      transform: translateX(-8px);
      transition: opacity 0.25s ease, transform 0.25s ease;
      box-shadow: 4px 0 0 #111;
    }

    /* ── FADE-IN state: terlihat ── */
    .taya-panel.open {
      opacity: 1;
      pointer-events: auto;
      transform: translateX(0);
    }

    /* Mobile: full-width sheet (PRD §5.1) */
    @media (max-width: 600px) {
      .taya-panel {
        left: 0;
        width: 100vw;
        max-width: 100vw;
        box-shadow: none;
        border-left: none;
      }
    }

    /* ── PANEL HEADER ── */
    .taya-panel-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 18px;
      background: var(--tw-black);
      color: var(--tw-cream);
      border-bottom: var(--tw-border);
      flex-shrink: 0;
    }

    .taya-panel-header-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .taya-panel-header-icon {
      width: 22px;
      height: 22px;
      flex-shrink: 0;
    }

    .taya-panel-title    { font-size: 14px; font-weight: 700; }
    .taya-panel-subtitle { font-size: 10px; color: #aaa; margin-top: 2px; }

    .taya-close {
      background: none;
      border: 1px solid #555;
      color: var(--tw-cream);
      font-family: 'Space Mono', monospace;
      font-size: 13px;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: var(--tw-radius);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.15s;
    }
    .taya-close svg { width: 14px; height: 14px; }
    .taya-close:hover { background: #333; }

    /* ── OFFLINE BANNER ── */
    .taya-offline-bar {
      display: none;
      background: #333;
      color: var(--tw-cream);
      font-size: 10px;
      text-align: center;
      padding: 6px 10px;
      border-bottom: 1px solid #000;
      font-family: 'Space Mono', monospace;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .taya-offline-bar svg { width: 12px; height: 12px; flex-shrink: 0; }
    .taya-offline-bar.show { display: flex; }

    /* ── MESSAGES ── */
    .taya-messages {
      flex: 1;
      overflow-y: auto;
      padding: 16px 14px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      scroll-behavior: smooth;
    }
    .taya-messages::-webkit-scrollbar       { width: 3px; }
    .taya-messages::-webkit-scrollbar-thumb { background: #bbb; border-radius: 2px; }

    .taya-msg {
      display: flex;
      flex-direction: column;
      animation: taya-fadein 0.22s ease;
    }
    @keyframes taya-fadein {
      from { opacity: 0; transform: translateY(4px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .taya-msg.user { align-items: flex-end; }
    .taya-msg.bot  { align-items: flex-start; }

    .taya-bubble {
      max-width: 80%;
      padding: 9px 13px;
      font-size: 12.5px;
      line-height: 1.65;
      border: var(--tw-border);
      border-radius: var(--tw-radius);
      word-break: break-word;
      white-space: pre-wrap;
    }
    .taya-msg.bot  .taya-bubble { background: var(--tw-cream); color: var(--tw-black); }
    .taya-msg.user .taya-bubble { background: var(--tw-black); color: var(--tw-cream); }

    /* ── FEEDBACK BUTTONS ── */
    .taya-feedback {
      display: flex;
      gap: 4px;
      margin-top: 4px;
    }
    .taya-fb-btn {
      background: none;
      border: 1px solid #ccc;
      border-radius: 3px;
      cursor: pointer;
      padding: 3px 7px;
      display: flex;
      align-items: center;
      transition: border-color 0.15s, transform 0.1s;
    }
    .taya-fb-btn svg { width: 12px; height: 12px; }
    .taya-fb-btn:hover { border-color: var(--tw-black); transform: scale(1.12); }
    .taya-fb-btn.active-up   { background: #d4f7d4; border-color: #2a7a2a; }
    .taya-fb-btn.active-down { background: #f7d4d4; border-color: #7a2a2a; }

    /* Typing dots */
    .taya-typing { display: none; padding: 0 14px 8px; }
    .taya-typing.show { display: flex; }
    .taya-typing-bubble {
      background: var(--tw-cream);
      border: var(--tw-border);
      border-radius: var(--tw-radius);
      padding: 9px 13px;
      display: flex;
      gap: 5px;
      align-items: center;
    }
    .taya-typing-bubble span {
      width: 7px; height: 7px;
      background: var(--tw-black);
      border-radius: 50%;
      animation: taya-bounce 1.1s infinite;
    }
    .taya-typing-bubble span:nth-child(2) { animation-delay: 0.18s; }
    .taya-typing-bubble span:nth-child(3) { animation-delay: 0.36s; }
    @keyframes taya-bounce {
      0%, 60%, 100% { transform: translateY(0); }
      30%           { transform: translateY(-5px); }
    }

    /* ── CHIPS ── */
    .taya-chips { display: flex; gap: 6px; flex-wrap: wrap; padding: 0 14px 8px; }
    .taya-chip {
      padding: 4px 9px;
      background: var(--tw-cream);
      border: var(--tw-border);
      border-radius: var(--tw-radius);
      font-family: 'Space Mono', monospace;
      font-size: 10.5px;
      cursor: pointer;
      transition: background 0.15s, color 0.15s;
    }
    .taya-chip:hover { background: var(--tw-black); color: var(--tw-cream); }

    /* ── INPUT AREA ── */
    .taya-input-wrap { border-top: var(--tw-border); background: var(--tw-cream); flex-shrink: 0; }
    .taya-input-row {
      display: flex; gap: 8px; align-items: flex-end;
      padding: 10px 14px;
      border-bottom: 1px solid #ccc;
    }
    .taya-input {
      flex: 1;
      background: transparent;
      border: var(--tw-border);
      border-radius: var(--tw-radius);
      padding: 7px 9px;
      font-family: 'Space Mono', monospace;
      font-size: 12px;
      color: var(--tw-black);
      resize: none;
      min-height: 36px;
      max-height: 90px;
      outline: none;
      line-height: 1.5;
    }
    .taya-input::placeholder { color: #999; }
    .taya-input:focus { border-color: #555; }

    .taya-send {
      background: var(--tw-black);
      color: var(--tw-cream);
      border: var(--tw-border);
      border-radius: var(--tw-radius);
      cursor: pointer;
      padding: 7px 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 0.15s;
      flex-shrink: 0;
    }
    .taya-send svg { width: 16px; height: 16px; }
    .taya-send:disabled { opacity: 0.35; cursor: not-allowed; }
    .taya-send:hover:not(:disabled) { opacity: 0.8; }

    .taya-meta { display: flex; justify-content: space-between; align-items: center; padding: 5px 14px 2px; }
    .taya-chars { font-size: 10px; color: #888; }
    .taya-chars.warn { color: #c0392b; }

    .taya-footer-row { display: flex; justify-content: space-between; align-items: center; padding: 4px 14px 10px; }
    .taya-note { font-size: 9.5px; color: #888; flex: 1; }
    .taya-reset {
      background: none;
      border: 1px solid #bbb;
      border-radius: var(--tw-radius);
      font-family: 'Space Mono', monospace;
      font-size: 10px;
      color: #666;
      cursor: pointer;
      padding: 3px 8px;
    }
    .taya-reset:hover { border-color: var(--tw-black); color: var(--tw-black); }

    /* Retry button */
    .taya-retry {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      margin-top: 5px;
      background: none;
      border: 1px solid var(--tw-black);
      border-radius: var(--tw-radius);
      font-family: 'Space Mono', monospace;
      font-size: 10px;
      cursor: pointer;
      padding: 2px 7px;
      color: var(--tw-black);
    }
    .taya-retry svg { width: 11px; height: 11px; }
    .taya-msg.user .taya-retry { border-color: var(--tw-cream); color: var(--tw-cream); }
  `;

  const styleTag = document.createElement('style');
  styleTag.textContent = css;
  document.head.appendChild(styleTag);

  // ── Build DOM ────────────────────────────────────────────────────────────

  const overlay = document.createElement('div');
  overlay.className = 'taya-overlay';
  overlay.setAttribute('aria-hidden', 'true');

  const panel = document.createElement('div');
  panel.id = 'tayaPanel';
  panel.className = 'taya-panel';
  panel.setAttribute('role', 'dialog');
  panel.setAttribute('aria-modal', 'true');
  panel.setAttribute('aria-label', 'Chat dengan Taya');

  panel.innerHTML = `
    <div class="taya-panel-header">
      <div class="taya-panel-header-left">
        <span class="taya-panel-header-icon">${SVG.hand}</span>
        <div>
          <div class="taya-panel-title">Tanya Taya</div>
          <div class="taya-panel-subtitle">Asisten AI — jawaban berdasarkan profil Attaya</div>
        </div>
      </div>
      <button class="taya-close" id="tayaClose" aria-label="Tutup panel">${SVG.close}</button>
    </div>

    <div class="taya-offline-bar" id="tayaOffline" role="alert">
      ${SVG.warning}
      <span>Kamu sedang offline. Pesan tersimpan, coba kirim saat koneksi kembali.</span>
    </div>

    <div class="taya-messages" id="tayaMsgs" aria-live="polite" aria-atomic="false">
      <div class="taya-msg bot">
        <div class="taya-bubble">
          Hai! Aku asisten AI Taya. Tanya apa saja soal skill, proyek, atau cara menghubunginya.
        </div>
      </div>
    </div>

    <div class="taya-typing" id="tayaTyping" aria-live="polite" aria-label="Taya sedang mengetik">
      <div class="taya-typing-bubble"><span></span><span></span><span></span></div>
    </div>

    <div class="taya-chips" id="tayaChips">
      <button class="taya-chip">Apa skill utamanya?</button>
      <button class="taya-chip">Lihat proyeknya</button>
      <button class="taya-chip">Cara menghubungi</button>
      <button class="taya-chip">Download CV</button>
    </div>

    <div class="taya-input-wrap">
      <div class="taya-input-row">
        <textarea
          id="tayaInput"
          class="taya-input"
          placeholder="Ketik pertanyaan... (maks 500 karakter)"
          rows="1"
          maxlength="500"
          aria-label="Tulis pesan"
        ></textarea>
        <button class="taya-send" id="tayaSend" aria-label="Kirim pesan">${SVG.send}</button>
      </div>
      <div class="taya-meta">
        <span class="taya-chars" id="tayaChars">0 / 500</span>
      </div>
      <div class="taya-footer-row">
        <span class="taya-note">Jawaban AI bisa keliru. Cek email untuk konfirmasi.</span>
        <button class="taya-reset" id="tayaReset">Reset</button>
      </div>
    </div>
  `;

  // Tombol tangan — ikon SVG, bukan emoji
  const handBtn = document.createElement('button');
  handBtn.className = 'taya-hand-btn';
  handBtn.setAttribute('aria-label', 'Tanya asisten AI tentang Attaya');
  handBtn.setAttribute('aria-expanded', 'false');
  handBtn.setAttribute('aria-controls', 'tayaPanel');
  handBtn.innerHTML = `<span class="taya-hand-icon">${SVG.hand}</span>`;

  document.body.appendChild(overlay);
  document.body.appendChild(panel);

  // ── Inject tombol ke sidebar ──────────────────────────────────────────────
  function injectButton() {
    const sidebar = document.querySelector(SIDEBAR_SELECTOR);
    if (sidebar) {
      sidebar.appendChild(handBtn);
      return;
    }
    // Fallback: fixed bottom-left
    handBtn.style.cssText = `
      position: fixed;
      bottom: 40px;
      left: 14px;
      z-index: 9997;
      box-shadow: 3px 3px 0 #111;
    `;
    document.body.appendChild(handBtn);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectButton);
  } else {
    injectButton();
  }

  // ── State ──────────────────────────────────────────────────────────────────
  let chatHistory = [];
  let pendingMsg  = null;
  let isOpen      = false;
  let sessionId   = null;
  let visitorId   = null;

  try {
    visitorId = localStorage.getItem('taya_visitor_id');
    if (!visitorId) {
      visitorId = 'v_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
      localStorage.setItem('taya_visitor_id', visitorId);
    }
  } catch (_) {}

  // ── Helper: header request ────────────────────────────────────────────────
  function buildHeaders() {
    const h = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
    if (CSRF_TOKEN) h['X-CSRF-TOKEN'] = CSRF_TOKEN;
    return h;
  }

  // ── Panel open/close — FADE IN/OUT ────────────────────────────────────────
  function openPanel() {
    isOpen = true;
    panel.classList.add('open');
    overlay.classList.add('open');
    handBtn.setAttribute('aria-expanded', 'true');
    document.getElementById('tayaInput').focus();
    document.addEventListener('keydown', onEsc);
    sendEvent('open_chat', 'chat_panel');
  }

  function closePanel() {
    isOpen = false;
    panel.classList.remove('open');
    overlay.classList.remove('open');
    handBtn.setAttribute('aria-expanded', 'false');
    handBtn.focus();
    document.removeEventListener('keydown', onEsc);
    sendEvent('close_chat', 'chat_panel');
  }

  function onEsc(e) { if (e.key === 'Escape') closePanel(); }

  handBtn.addEventListener('click', () => isOpen ? closePanel() : openPanel());
  overlay.addEventListener('click', closePanel);
  document.getElementById('tayaClose').addEventListener('click', closePanel);

  // ── Auto-wave touch devices (PRD §4.2) ────────────────────────────────────
  const icon = handBtn.querySelector('.taya-hand-icon');
  const isTouchDevice = () => window.matchMedia('(hover: none)').matches;

  if (isTouchDevice()) {
    setTimeout(() => {
      icon.classList.add('taya-auto-wave');
      icon.addEventListener('animationend', () => icon.classList.remove('taya-auto-wave'), { once: true });
    }, 1500);
    setInterval(() => {
      if (isOpen) return;
      icon.classList.add('taya-auto-wave');
      icon.addEventListener('animationend', () => icon.classList.remove('taya-auto-wave'), { once: true });
    }, 8000);
  }

  // ── Offline detection ─────────────────────────────────────────────────────
  const offlineBar = document.getElementById('tayaOffline');
  window.addEventListener('online', () => {
    offlineBar.classList.remove('show');
    if (pendingMsg) { document.getElementById('tayaInput').value = pendingMsg; pendingMsg = null; }
  });
  window.addEventListener('offline', () => offlineBar.classList.add('show'));

  // ── Chips ─────────────────────────────────────────────────────────────────
  document.getElementById('tayaChips').querySelectorAll('.taya-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      document.getElementById('tayaInput').value = chip.textContent;
      document.getElementById('tayaChips').style.display = 'none';
      sendMessage();
    });
  });

  // ── Input helpers ─────────────────────────────────────────────────────────
  const inputEl    = document.getElementById('tayaInput');
  const sendBtnEl  = document.getElementById('tayaSend');
  const charsEl    = document.getElementById('tayaChars');

  inputEl.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 90) + 'px';
    const len = this.value.length;
    charsEl.textContent = `${len} / 500`;
    charsEl.classList.toggle('warn', len > 450);
  });

  inputEl.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
  });

  // ── Reset ──────────────────────────────────────────────────────────────────
  document.getElementById('tayaReset').addEventListener('click', () => {
    if (!confirm('Reset percakapan? Riwayat chat akan hilang.')) return;
    chatHistory = [];
    sessionId   = null;
    document.getElementById('tayaMsgs').innerHTML = `
      <div class="taya-msg bot">
        <div class="taya-bubble">Hai! Aku asisten AI Taya. Tanya apa saja soal skill, proyek, atau cara menghubunginya.</div>
      </div>`;
    document.getElementById('tayaChips').style.display = 'flex';
  });

  // ── Append bubble ─────────────────────────────────────────────────────────
  function appendMsg(role, text, options) {
    options = options || {};
    const msgs = document.getElementById('tayaMsgs');
    const row  = document.createElement('div');
    row.className = `taya-msg ${role}`;

    const bubble = document.createElement('div');
    bubble.className = 'taya-bubble';
    bubble.textContent = text;
    row.appendChild(bubble);

    if (options.retryable) {
      const btn = document.createElement('button');
      btn.className   = 'taya-retry';
      btn.innerHTML   = SVG.retry + ' Coba lagi';
      btn.onclick = () => {
        row.remove();
        const lastUser = [...chatHistory].reverse().find(m => m.role === 'user');
        if (lastUser) { inputEl.value = lastUser.content; sendMessage(); }
      };
      bubble.appendChild(document.createElement('br'));
      bubble.appendChild(btn);
    }

    if (role === 'bot' && options.messageId && sessionId) {
      const fbRow = document.createElement('div');
      fbRow.className = 'taya-feedback';
      fbRow.appendChild(makeFeedbackBtn(SVG.thumbUp,   'thumbs_up',   options.messageId, fbRow));
      fbRow.appendChild(makeFeedbackBtn(SVG.thumbDown, 'thumbs_down', options.messageId, fbRow));
      row.appendChild(fbRow);
    }

    msgs.appendChild(row);
    msgs.scrollTop = msgs.scrollHeight;
    return row;
  }

  function makeFeedbackBtn(svgStr, rating, messageId, fbRow) {
    const btn = document.createElement('button');
    btn.className = 'taya-fb-btn';
    btn.innerHTML = svgStr;
    btn.title     = rating === 'thumbs_up' ? 'Jawaban bagus' : 'Jawaban kurang tepat';
    btn.setAttribute('aria-label', btn.title);
    btn.addEventListener('click', () => {
      fbRow.querySelectorAll('.taya-fb-btn').forEach(b => b.classList.remove('active-up', 'active-down'));
      btn.classList.add(rating === 'thumbs_up' ? 'active-up' : 'active-down');
      sendFeedback(messageId, rating);
    });
    return btn;
  }

  // ── Loading state ─────────────────────────────────────────────────────────
  function setLoading(on) {
    sendBtnEl.disabled = on;
    document.getElementById('tayaTyping').classList.toggle('show', on);
    if (on) { const m = document.getElementById('tayaMsgs'); m.scrollTop = m.scrollHeight; }
  }

  // ── Feedback & Event ──────────────────────────────────────────────────────
  function sendFeedback(messageId, rating) {
    if (!sessionId) return;
    fetch(FEEDBACK_ENDPOINT, { method: 'POST', headers: buildHeaders(),
      body: JSON.stringify({ session_id: sessionId, message_id: messageId, rating }) }).catch(() => {});
  }

  function sendEvent(eventType, target) {
    fetch(EVENT_ENDPOINT, { method: 'POST', headers: buildHeaders(),
      body: JSON.stringify({ session_id: sessionId, event_type: eventType, target }) }).catch(() => {});
  }

  // ── Send message ───────────────────────────────────────────────────────────
  async function sendMessage() {
    const text = inputEl.value.trim();
    if (!text || sendBtnEl.disabled) return;

    if (!navigator.onLine) {
      pendingMsg = text;
      offlineBar.classList.add('show');
      return;
    }

    document.getElementById('tayaChips').style.display = 'none';
    appendMsg('user', text);
    chatHistory.push({ role: 'user', content: text });

    inputEl.value = '';
    inputEl.style.height = 'auto';
    charsEl.textContent  = '0 / 500';
    setLoading(true);

    try {
      const res = await fetch(CHAT_ENDPOINT, {
        method: 'POST',
        headers: buildHeaders(),
        body: JSON.stringify({ message: text, history: chatHistory, session_id: sessionId, visitor_id: visitorId }),
      });

      const data = await res.json();

      if (res.status === 429) {
        appendMsg('bot', data.error ?? 'Terlalu banyak pesan, coba lagi sebentar.');
      } else if (!res.ok || data.error) {
        appendMsg('bot', data.error ?? 'Terjadi kesalahan. Coba lagi ya.', { retryable: data.retryable ?? false });
      } else {
        if (data.session_id) sessionId = data.session_id;
        appendMsg('bot', data.reply, { messageId: data.message_id });
        chatHistory.push({ role: 'assistant', content: data.reply });
      }
    } catch (_) {
      pendingMsg = text;
      appendMsg('bot', 'Koneksi terputus. Pesan tersimpan — coba kirim lagi saat online.');
    } finally {
      setLoading(false);
    }
  }

})();
