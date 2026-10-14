<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Taya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #FFF0E5;
            --black: #111111;
            --border: 2px solid #111111;
            --radius: 4px;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--cream);
            color: var(--black);
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--black);
            color: var(--cream);
            padding: 0 40px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-brand {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
        }

        .topbar-brand span {
            background: var(--cream);
            color: var(--black);
            padding: 2px 8px;
            border-radius: 3px;
            margin-left: 8px;
            font-size: 10px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 11px;
            color: #aaa;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #555;
            color: #ccc;
            padding: 5px 14px;
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            border-radius: var(--radius);
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: border-color 0.15s, color 0.15s;
        }
        .btn-logout:hover { border-color: #ccc; color: #fff; }

        /* ── MAIN ── */
        .main { padding: 36px 40px; max-width: 1100px; margin: 0 auto; }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .page-sub {
            font-size: 11px;
            color: #888;
            margin-bottom: 32px;
        }

        /* ── STAT CARDS ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 36px;
        }

        .stat-card {
            border: var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            background: #fff8f2;
        }

        .stat-card-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 36px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card-sub {
            font-size: 10px;
            color: #aaa;
            margin-top: 6px;
        }

        /* ── SECTION HEADER ── */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #888;
        }

        .badge-new {
            background: var(--black);
            color: var(--cream);
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.05em;
        }

        /* ── MINI CHART ── */
        .chart-wrap {
            border: var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            background: #fff8f2;
            margin-bottom: 36px;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 80px;
            margin-top: 16px;
        }

        .chart-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            height: 100%;
            justify-content: flex-end;
        }

        .chart-bar {
            width: 100%;
            background: var(--black);
            border-radius: 2px 2px 0 0;
            min-height: 3px;
            transition: opacity 0.2s;
        }
        .chart-bar:hover { opacity: 0.7; }

        .chart-date {
            font-size: 9px;
            color: #aaa;
            white-space: nowrap;
        }

        /* ── DEVICE PILLS ── */
        .device-pills {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }

        .device-pill {
            border: var(--border);
            border-radius: var(--radius);
            padding: 10px 18px;
            font-size: 11px;
        }

        .device-pill strong {
            font-size: 20px;
            display: block;
            margin-bottom: 2px;
        }

        /* ── TABLE ── */
        .table-wrap {
            border: var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .table-wrap table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-wrap thead {
            background: var(--black);
            color: var(--cream);
        }

        .table-wrap th {
            padding: 11px 16px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-align: left;
        }

        .table-wrap td {
            padding: 12px 16px;
            font-size: 11px;
            border-bottom: 1px solid #e8ddd4;
            vertical-align: top;
        }

        .table-wrap tr:last-child td { border-bottom: none; }

        .table-wrap tr:hover td { background: #fff3ea; }

        .msg-preview {
            color: #666;
            font-size: 11px;
            line-height: 1.6;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 3px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .status-new     { background: #fde8e8; color: #c0392b; border: 1px solid #e0b0b0; }
        .status-read    { background: #e8f4e8; color: #2a7a2a; border: 1px solid #b0d8b0; }
        .status-replied { background: #e8eef8; color: #2a4a8a; border: 1px solid #b0c4e8; }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: #aaa;
            font-size: 12px;
        }

        @media (max-width: 640px) {
            .main { padding: 24px 20px; }
            .topbar { padding: 0 20px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .table-wrap th:nth-child(3),
            .table-wrap td:nth-child(3) { display: none; }
        }
    </style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-brand">
        TAYA <span>ADMIN</span>
    </div>
    <div class="topbar-right">
        <span>attayaarkarna12@gmail.com</span>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">Logout →</button>
        </form>
    </div>
</header>

<div class="main">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Data real-time portofolio kamu — diperbarui setiap kunjungan.</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card-label">Total Pengunjung</div>
            <div class="stat-card-value">{{ number_format($totalSessions) }}</div>
            <div class="stat-card-sub">sesi chat dibuka</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Hari Ini</div>
            <div class="stat-card-value">{{ $todaySessions }}</div>
            <div class="stat-card-sub">sesi baru hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">7 Hari Terakhir</div>
            <div class="stat-card-value">{{ $weekSessions }}</div>
            <div class="stat-card-sub">sesi minggu ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Total Pesan Chat</div>
            <div class="stat-card-value">{{ number_format($totalMessages) }}</div>
            <div class="stat-card-sub">pesan terkirim</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Pesan Kontak</div>
            <div class="stat-card-value">{{ $totalContacts }}</div>
            <div class="stat-card-sub">
                @if($newContacts > 0)
                    <span style="color:#c0392b; font-weight:700;">{{ $newContacts }} belum dibaca</span>
                @else
                    semua sudah dibaca
                @endif
            </div>
        </div>
    </div>

    <!-- MINI BAR CHART: 7 hari terakhir -->
    @if($dailyStats->count())
    <div class="chart-wrap">
        <div class="section-label">Kunjungan 7 Hari Terakhir</div>
        @php
            $maxCount = $dailyStats->max('count') ?: 1;
            // Isi hari yang kosong
            $days = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $found = $dailyStats->firstWhere('date', $date);
                $days->push(['date' => $date, 'count' => $found ? $found->count : 0]);
            }
        @endphp
        <div class="chart-bars">
            @foreach($days as $day)
            @php $pct = round(($day['count'] / $maxCount) * 100); @endphp
            <div class="chart-col" title="{{ $day['date'] }}: {{ $day['count'] }} sesi">
                <div class="chart-bar" style="height: {{ max($pct, 3) }}%;"></div>
                <span class="chart-date">{{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- DEVICE BREAKDOWN -->
    @if($deviceStats->count())
    <div class="section-head" style="margin-bottom:12px;">
        <span class="section-label">Perangkat Pengunjung</span>
    </div>
    <div class="device-pills">
        @foreach($deviceStats as $d)
        <div class="device-pill">
            <strong>{{ $d->count }}</strong>
            {{ ucfirst($d->device_type) }}
        </div>
        @endforeach
    </div>
    @endif

    <!-- TABEL PESAN KONTAK -->
    <div class="section-head">
        <span class="section-label">Pesan Kontak</span>
        @if($newContacts > 0)
            <span class="badge-new">{{ $newContacts }} baru</span>
        @endif
    </div>

    <div class="table-wrap">
        @if($contactMessages->count())
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contactMessages as $msg)
                <tr>
                    <td style="font-weight:700; white-space:nowrap;">{{ $msg->sender_name }}</td>
                    <td>
                        <a href="mailto:{{ $msg->sender_email }}"
                           style="color:var(--black); text-decoration:none; border-bottom:1px solid #ccc;">
                            {{ $msg->sender_email }}
                        </a>
                    </td>
                    <td>
                        <div class="msg-preview" title="{{ $msg->message }}">
                            {{ $msg->message }}
                        </div>
                    </td>
                    <td style="white-space:nowrap; color:#888;">
                        {{ \Carbon\Carbon::parse($msg->created_at)->locale('id')->isoFormat('D MMM YYYY') }}<br>
                        <span style="font-size:10px;">{{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $msg->status }}">{{ $msg->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            Belum ada pesan kontak masuk.
        </div>
        @endif
    </div>

</div>
</body>
</html>
