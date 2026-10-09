<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attaya Arkarna — Portofolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ── Base: neo-brutalist krem-hitam (PRD §5.3) ── */
        :root {
            --cream:  #FFF0E5;
            --black:  #111111;
            --border: 2px solid #111111;
            --radius: 4px;
            --sidebar-w: 56px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--cream);
            color: var(--black);
            min-height: 100vh;
        }

        /* ── SIDEBAR KIRI (PRD §4.1 — tombol tangan di bawah Facebook) ── */
        .sidebar-social {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--black);
            border-right: var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 10px;
            z-index: 100;
        }

        .sidebar-logo {
            font-size: 11px;
            font-weight: 700;
            color: var(--cream);
            writing-mode: vertical-rl;
            text-orientation: mixed;
            letter-spacing: 0.15em;
            margin-bottom: 10px;
            transform: rotate(180deg);
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: transparent;
            border: 1px solid #444;
            border-radius: 4px;
            color: var(--cream);
            font-size: 14px;
            text-decoration: none;
            transition: background 0.15s, border-color 0.15s;
            position: relative;
        }

        .social-icon:hover { background: #2a2a2a; border-color: #888; }

        /* Tooltip untuk ikon sosial */
        .social-icon::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            color: var(--cream);
            font-size: 10px;
            padding: 3px 7px;
            border-radius: 3px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s;
            z-index: 200;
        }
        .social-icon:hover::after { opacity: 1; }

        /* SVG ikon sederhana (inline) */
        .social-icon svg { width: 16px; height: 16px; fill: currentColor; }

        /* ── IKON SVG UMUM ── */
        .ico { display: inline-flex; align-items: center; vertical-align: middle; flex-shrink: 0; }
        .ico svg { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ico-sm svg  { width: 14px; height: 14px; }
        .ico-md svg  { width: 18px; height: 18px; }
        .ico-lg svg  { width: 36px; height: 36px; }
        .ico-xl svg  { width: 52px; height: 52px; }

        /* avatar hero pakai ikon, bukan emoji */
        .hero-avatar .ico-xl svg { width: 64px; height: 64px; stroke: var(--cream); }

        /* ── MAIN CONTENT ── */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        /* ── HERO ── */
        .hero {
            padding: 60px 48px 48px;
            border-bottom: var(--border);
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 40px;
            align-items: center;
        }

        .hero-tag {
            display: inline-block;
            background: var(--black);
            color: var(--cream);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            padding: 4px 10px;
            border-radius: var(--radius);
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .hero-name {
            font-size: clamp(32px, 5vw, 56px);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .hero-name span {
            display: inline-block;
            background: var(--black);
            color: var(--cream);
            padding: 0 6px;
        }

        .hero-subtitle {
            margin-top: 16px;
            font-size: 13px;
            line-height: 1.8;
            color: #444;
            max-width: 480px;
        }

        .hero-cta {
            display: flex;
            gap: 10px;
            margin-top: 28px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            border-radius: var(--radius);
            cursor: pointer;
            text-decoration: none;
            border: var(--border);
            transition: background 0.15s, color 0.15s, transform 0.1s;
        }
        .btn:active { transform: translate(2px, 2px); }
        .btn-primary { background: var(--black); color: var(--cream); }
        .btn-primary:hover { background: #333; }
        .btn-outline { background: transparent; color: var(--black); }
        .btn-outline:hover { background: var(--black); color: var(--cream); }

        .hero-avatar {
            width: 160px;
            height: 160px;
            background: var(--black);
            border: var(--border);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            flex-shrink: 0;
        }

        /* ── STATS ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: var(--border);
        }

        .stat-item {
            padding: 24px 32px;
            border-right: var(--border);
        }
        .stat-item:last-child { border-right: none; }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── SKILLS ── */
        .section {
            padding: 40px 48px;
            border-bottom: var(--border);
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 24px;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }

        .skill-bar {
            border: var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .skill-bar-label {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .skill-bar-track {
            height: 6px;
            background: #e0cfc0;
            margin: 0 12px 10px;
            border-radius: 2px;
            overflow: hidden;
        }

        .skill-bar-fill {
            height: 100%;
            background: var(--black);
            border-radius: 2px;
            transition: width 1s ease;
        }

        /* ── PROJECTS ── */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .project-card {
            border: var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .project-card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 4px 4px 0 var(--black);
        }

        .project-cover {
            height: 120px;
            background: var(--black);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            border-bottom: var(--border);
        }

        .project-body { padding: 16px; }

        .project-type {
            font-size: 9px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 6px;
        }

        .project-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .project-desc {
            font-size: 11px;
            color: #555;
            line-height: 1.7;
        }

        .project-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .tag {
            font-size: 10px;
            background: var(--cream);
            border: 1px solid #ccc;
            padding: 2px 7px;
            border-radius: 3px;
        }

        /* ── CONTACT SECTION ── */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        .contact-info p {
            font-size: 12px;
            line-height: 1.9;
            color: #555;
        }

        .contact-info a {
            color: var(--black);
            font-weight: 700;
        }

        .contact-form { display: flex; flex-direction: column; gap: 12px; }

        .form-group { display: flex; flex-direction: column; gap: 4px; }

        .form-group label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #666;
        }

        .form-control {
            background: transparent;
            border: var(--border);
            border-radius: var(--radius);
            padding: 9px 12px;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            color: var(--black);
            outline: none;
            transition: border-color 0.15s;
        }
        .form-control:focus { border-color: #555; }
        .form-control::placeholder { color: #999; }

        textarea.form-control { resize: vertical; min-height: 100px; }

        #contactStatus {
            font-size: 11px;
            padding: 8px 12px;
            border-radius: var(--radius);
            display: none;
        }
        #contactStatus.ok   { display: block; background: #d4f7d4; border: 1px solid #2a7a2a; color: #1a4a1a; }
        #contactStatus.err  { display: block; background: #f7d4d4; border: 1px solid #7a2a2a; color: #4a1a1a; }

        /* ── FOOTER ── */
        .footer {
            margin-left: var(--sidebar-w);
            border-top: var(--border);
            padding: 20px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #888;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .hero { grid-template-columns: 1fr; padding: 40px 24px 32px; }
            .hero-avatar { width: 100px; height: 100px; font-size: 40px; }
            .stats { grid-template-columns: 1fr 1fr; }
            .stat-item:nth-child(3) { border-right: none; grid-column: span 2; }
            .section { padding: 32px 24px; }
            .contact-grid { grid-template-columns: 1fr; }
            .footer { padding: 16px 24px; flex-direction: column; gap: 8px; text-align: center; }
        }

        @media (max-width: 480px) {
            :root { --sidebar-w: 48px; }
            .stats { grid-template-columns: 1fr; }
            .stat-item { border-right: none !important; }
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR KIRI (PRD §4.1 — ikon sosial + tombol tangan akan di-inject widget.js) ── -->
<aside class="sidebar-social" role="complementary" aria-label="Tautan sosial media">
    <span class="sidebar-logo">TAYA</span>

    <a href="https://www.instagram.com/attaya.arkarna/" target="_blank" rel="noopener noreferrer"
       class="social-icon" aria-label="Instagram" data-tip="Instagram">
        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
    </a>

    <a href="https://www.linkedin.com/in/attaya-arkarna-a2b4a12b4/" target="_blank" rel="noopener noreferrer"
       class="social-icon" aria-label="LinkedIn" data-tip="LinkedIn">
        <svg viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
    </a>

    <a href="https://github.com/attayaarkarna" target="_blank" rel="noopener noreferrer"
       class="social-icon" aria-label="GitHub" data-tip="GitHub">
        <svg viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
    </a>

    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer"
       class="social-icon" aria-label="Facebook" data-tip="Facebook">
        <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
    </a>

    {{-- widget.js akan menyuntikkan tombol tangan di sini (PRD §4.1) --}}
</aside>

<!-- ── MAIN CONTENT ── -->
<main class="main">

    <!-- HERO -->
    <section class="hero" aria-labelledby="heroName">
        <div>
            <span class="hero-tag">Software Engineering · SMK Madinatul Qur'an</span>
            <h1 class="hero-name" id="heroName">
                Halo, aku<br>
                <span>Attaya Arkarna</span>
            </h1>
            <p class="hero-subtitle">
                Siswa SMK yang suka bikin website modern &amp; responsif, belajar Front-End dan Back-End,
                dan senang bereksperimen dengan desain UI/UX. Tinggal di Cikarang Utara, Bekasi.
            </p>
            <div class="hero-cta">
                <a href="https://www.attaya-arkarna.my.id/img/cvTaya.pdf" target="_blank" rel="noopener noreferrer"
                   class="btn btn-primary" aria-label="Download CV Attaya">
                    <span class="ico ico-sm" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M12 16l-4-4h3V4h2v8h3l-4 4z"/><path d="M20 18H4v2h16v-2z"/></svg>
                    </span>
                    Download CV
                </a>
                <a href="mailto:attayaarkarna12@gmail.com" class="btn btn-outline" aria-label="Kirim email ke Attaya">
                    <span class="ico ico-sm" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                    </span>
                    Kirim Email
                </a>
            </div>
        </div>
        <div class="hero-avatar" aria-hidden="true">
            <span class="ico ico-xl">
                <svg viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </span>
        </div>
    </section>

    <!-- STATS -->
    <div class="stats" role="list" aria-label="Statistik ringkas">
        <div class="stat-item" role="listitem">
            <div class="stat-value">10+</div>
            <div class="stat-label">Proyek Selesai</div>
        </div>
        <div class="stat-item" role="listitem">
            <div class="stat-value">80%</div>
            <div class="stat-label">Tingkat Penyelesaian</div>
        </div>
        <div class="stat-item" role="listitem">
            <div class="stat-value">89%</div>
            <div class="stat-label">Skor Desain Grafis</div>
        </div>
    </div>

    <!-- SKILLS -->
    <section class="section" aria-labelledby="skillsTitle">
        <div class="section-title" id="skillsTitle">Skills &amp; Tools</div>
        <div class="skills-grid">
            <div class="skill-bar">
                <div class="skill-bar-label"><span>HTML5</span><span>90%</span></div>
                <div class="skill-bar-track"><div class="skill-bar-fill" style="width:90%"></div></div>
            </div>
            <div class="skill-bar">
                <div class="skill-bar-label"><span>Desain Grafis</span><span>89%</span></div>
                <div class="skill-bar-track"><div class="skill-bar-fill" style="width:89%"></div></div>
            </div>
            <div class="skill-bar">
                <div class="skill-bar-label"><span>PHP</span><span>70%</span></div>
                <div class="skill-bar-track"><div class="skill-bar-fill" style="width:70%"></div></div>
            </div>
            <div class="skill-bar">
                <div class="skill-bar-label"><span>JavaScript</span><span>60%</span></div>
                <div class="skill-bar-track"><div class="skill-bar-fill" style="width:60%"></div></div>
            </div>
            <div class="skill-bar">
                <div class="skill-bar-label"><span>CSS / Tailwind</span><span>50%</span></div>
                <div class="skill-bar-track"><div class="skill-bar-fill" style="width:50%"></div></div>
            </div>
        </div>

        <div style="margin-top:24px; display:flex; flex-wrap:wrap; gap:8px;" aria-label="Teknologi yang dipakai">
            @foreach(['React','Next.js','Git','GitHub','Firebase','Vercel','Laravel'] as $tech)
            <span class="tag">{{ $tech }}</span>
            @endforeach
        </div>
    </section>

    <!-- PROJECTS -->
    <section class="section" aria-labelledby="projectsTitle">
        <div class="section-title" id="projectsTitle">Proyek</div>
        <div class="projects-grid">

            <article class="project-card">
                <div class="project-cover">
                    <span class="ico ico-lg" aria-hidden="true">
                        <!-- shopping bag / marketplace icon -->
                        <svg viewBox="0 0 24 24" stroke="var(--cream)"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </span>
                </div>
                <div class="project-body">
                    <div class="project-type">Website</div>
                    <h3 class="project-title">Meowketplace</h3>
                    <p class="project-desc">Website bertema marketplace kucing. Desain modern dengan antarmuka yang ramah pengguna.</p>
                    <div class="project-tags">
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                        <span class="tag">JavaScript</span>
                    </div>
                </div>
            </article>

            <article class="project-card">
                <div class="project-cover">
                    <span class="ico ico-lg" aria-hidden="true">
                        <!-- settings/tool icon -->
                        <svg viewBox="0 0 24 24" stroke="var(--cream)"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    </span>
                </div>
                <div class="project-body">
                    <div class="project-type">Figma Design</div>
                    <h3 class="project-title">Sgarage</h3>
                    <p class="project-desc">Desain UI/UX bertema otomotif menggunakan Figma. Fokus pada estetika dan keterbacaan.</p>
                    <div class="project-tags">
                        <span class="tag">Figma</span>
                        <span class="tag">UI/UX</span>
                    </div>
                </div>
            </article>

            <article class="project-card">
                <div class="project-cover">
                    <span class="ico ico-lg" aria-hidden="true">
                        <!-- eye icon -->
                        <svg viewBox="0 0 24 24" stroke="var(--cream)"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
                <div class="project-body">
                    <div class="project-type">Figma Design</div>
                    <h3 class="project-title">Eyfy</h3>
                    <p class="project-desc">Proyek desain Figma dengan pendekatan clean dan minimalis.</p>
                    <div class="project-tags">
                        <span class="tag">Figma</span>
                        <span class="tag">UI/UX</span>
                    </div>
                </div>
            </article>

            <article class="project-card">
                <div class="project-cover">
                    <span class="ico ico-lg" aria-hidden="true">
                        <!-- clock/timer icon untuk produktivitas -->
                        <svg viewBox="0 0 24 24" stroke="var(--cream)"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                </div>
                <div class="project-body">
                    <div class="project-type">Website</div>
                    <h3 class="project-title">Bloomdoro</h3>
                    <p class="project-desc">Aplikasi web produktivitas dengan pendekatan desain yang menyenangkan.</p>
                    <div class="project-tags">
                        <span class="tag">HTML</span>
                        <span class="tag">CSS</span>
                        <span class="tag">JavaScript</span>
                    </div>
                </div>
            </article>

        </div>
    </section>

    <!-- PENDIDIKAN -->
    <section class="section" aria-labelledby="eduTitle">
        <div class="section-title" id="eduTitle">Pendidikan</div>
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div style="border:var(--border); border-radius:var(--radius); padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                <div>
                    <div style="font-weight:700; font-size:13px;">SMK Madinatul Qur'an</div>
                    <div style="font-size:11px; color:#666; margin-top:2px;">Software Engineering</div>
                </div>
                <span style="font-size:10px; color:#888; border:1px solid #ccc; padding:3px 10px; border-radius:3px;">2022 — 2028</span>
            </div>
            <div style="border:var(--border); border-radius:var(--radius); padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
                <div>
                    <div style="font-weight:700; font-size:13px;">SMPIT Rabbaani Islamic School</div>
                    <div style="font-size:11px; color:#666; margin-top:2px;">SMP Islam</div>
                </div>
                <span style="font-size:10px; color:#888; border:1px solid #ccc; padding:3px 10px; border-radius:3px;">2015 — 2021</span>
            </div>
        </div>
    </section>

    <!-- KONTAK -->
    <section class="section" aria-labelledby="contactTitle">
        <div class="section-title" id="contactTitle">Hubungi Aku</div>
        <div class="contact-grid">
            <div class="contact-info">
                <p>
                    Punya proyek atau mau ngobrol soal kolaborasi?<br>
                    Kirim pesan langsung atau hubungi via:<br><br>
                    <span class="ico ico-sm" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
                    </span>
                    <a href="mailto:attayaarkarna12@gmail.com">attayaarkarna12@gmail.com</a><br>
                    <span class="ico ico-sm" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.87a16 16 0 0 0 6.29 6.29l1.76-1.76a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>
                    </span>
                    <a href="https://wa.me/6288102449023" target="_blank" rel="noopener noreferrer">+62 881 0244 90239</a><br>
                    <span class="ico ico-sm" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    Senin–Jumat, 09.00–17.00 WIB<br><br>
                    Atau klik tombol
                    <span class="ico ico-sm" aria-hidden="true" style="display:inline-flex;vertical-align:middle;">
                        <svg viewBox="0 0 24 24"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                    </span>
                    di kiri untuk ngobrol dengan AI Taya.
                </p>
            </div>

            <form class="contact-form" id="contactForm" aria-label="Form pesan">
                <div class="form-group">
                    <label for="senderName">Nama</label>
                    <input type="text" id="senderName" class="form-control" placeholder="Nama kamu" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="senderEmail">Email</label>
                    <input type="email" id="senderEmail" class="form-control" placeholder="email@kamu.com" required maxlength="150">
                </div>
                <div class="form-group">
                    <label for="senderMessage">Pesan</label>
                    <textarea id="senderMessage" class="form-control" placeholder="Tulis pesanmu di sini..." required maxlength="2000"></textarea>
                </div>
                <div id="contactStatus" role="alert" aria-live="polite"></div>
                <button type="submit" class="btn btn-primary" id="contactSubmit">Kirim Pesan →</button>
            </form>
        </div>
    </section>

</main>

<footer class="footer" role="contentinfo">
    <span>© 2026 Attaya Arkarna — dibuat dengan
        <span class="ico ico-sm" aria-hidden="true" style="color:#c0392b;">
            <svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </span>
        dan banyak kopi
    </span>
    <span>Lagi berkembang dan semangat belajar
        <span class="ico ico-sm" aria-hidden="true">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </span>
    </span>
</footer>

<!-- Widget chatbot (PRD §4, §5) — inject setelah DOM siap -->
<script src="{{ asset('widget.js') }}"></script>

<script>
// ── Contact form handler — kirim ke POST /api/contact ──────────────────────
document.getElementById('contactForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const statusEl = document.getElementById('contactStatus');
    const submitBtn = document.getElementById('contactSubmit');
    const name    = document.getElementById('senderName').value.trim();
    const email   = document.getElementById('senderEmail').value.trim();
    const message = document.getElementById('senderMessage').value.trim();

    if (!name || !email || !message) return;

    submitBtn.disabled   = true;
    submitBtn.textContent = 'Mengirim...';
    statusEl.className   = '';
    statusEl.textContent = '';

    try {
        const res = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                sender_name:  name,
                sender_email: email,
                message:      message,
                // session_id dari widget jika sudah dibuka (diakses via taya_session di window)
                session_id:   window.__tayaSessionId || null,
            }),
        });

        const data = await res.json();

        if (res.ok && data.ok) {
            statusEl.className   = 'ok';
            statusEl.textContent = data.message || 'Pesan terkirim!';
            this.reset();
        } else {
            statusEl.className   = 'err';
            statusEl.textContent = data.error || 'Gagal mengirim. Coba lagi.';
        }
    } catch (_) {
        statusEl.className   = 'err';
        statusEl.textContent = 'Koneksi bermasalah. Coba lagi nanti.';
    } finally {
        submitBtn.disabled   = false;
        submitBtn.textContent = 'Kirim Pesan →';
    }
});
</script>

</body>
</html>
