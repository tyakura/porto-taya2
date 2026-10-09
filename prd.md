# PRD — Tombol Chatbot "Tangan Melambai" di Portofolio Attaya Arkarna

| Item | Detail |
|---|---|
| Produk | Portofolio Attaya Arkarna (https://www.attaya-arkarna.my.id/) |
| Fitur | Tombol ikon tangan di navbar/sidebar yang membuka chatbot tentang Attaya |
| Pemilik | Attaya Arkarna |
| Versi dokumen | 2.0 |
| Tanggal | 7 Oktober 2026 |
| Status | Draft |

---

## 1. Ringkasan

Tambahkan satu tombol baru di sidebar kiri, **tepat di bawah ikon Facebook**. Tombol ini berupa **ikon tangan**. Saat di-hover, tangan **melambai**. Saat diklik, **panel chatbot meluncur dari samping** dan pengunjung bisa bertanya apa saja tentang Attaya (skill, proyek, pendidikan, kontak). Jawaban dibuat oleh AI Groq yang memakai `identity.md` sebagai basis pengetahuan.

Tombol ini sengaja dibuat sebagai "sapaan": lambaian tangan mengundang pengunjung untuk ngobrol, bukan sekadar ikon sosial media.

## 2. Tujuan

| # | Tujuan | Indikator |
|---|---|---|
| G1 | Pengunjung menemukan chatbot dengan mudah | ≥ 15% pengunjung hover/klik tombol |
| G2 | Pengunjung mendapat jawaban cepat tentang Attaya | ≥ 60% pertanyaan terjawab tanpa "tidak tahu" |
| G3 | Mendorong kontak langsung | Naiknya klik email/WhatsApp/CV setelah chat |
| G4 | Tampilan menyatu dengan gaya website | Tombol dan panel konsisten dengan gaya neo-brutalist krem-hitam |

**Di luar cakupan:** login pengunjung, riwayat chat lintas perangkat, suara/voice, dan chat dengan Attaya secara live (manusia).

## 3. Alur Pengguna

1. Pengunjung melihat sidebar kiri: Instagram, LinkedIn, GitHub, Facebook, lalu **ikon tangan**.
2. Pengunjung mengarahkan kursor ke ikon tangan, lalu tangan **melambai** (animasi).
3. Pengunjung mengklik, lalu **panel chat meluncur dari samping** dengan pesan sambutan.
4. Pengunjung mengetik atau memilih saran pertanyaan, lalu AI menjawab.
5. Pengunjung menutup panel (tombol X, tombol Esc, atau klik di luar panel).

## 4. Spesifikasi Tombol (F1)

### 4.1 Posisi dan tampilan

- Posisi: **di bawah tombol Facebook**, dalam tumpukan ikon sosial di sidebar kiri.
- Ukuran dan bentuk: sama dengan tombol sosial lain (kotak bersudut membulat, border hitam tipis, latar transparan/krem).
- Ikon: tangan (misalnya ikon `hand` atau emoji 👋 sebagai fallback), warna hitam `#111111`.
- Label aksesibilitas: `aria-label="Tanya asisten AI tentang Attaya"`, `aria-expanded` mengikuti status panel, dan tooltip "Tanya aku 👋" saat hover.

### 4.2 Animasi hover (lambaian)

- Tangan berayun bolak-balik seperti melambai, dengan titik putar di pergelangan (bagian bawah ikon).
- Durasi sekitar 0,8–1 detik, berulang selama kursor masih di atas tombol.
- Di layar sentuh (tanpa hover): lambaian otomatis satu kali saat halaman selesai dimuat, dan berulang pelan tiap beberapa detik **hanya jika** panel belum pernah dibuka.
- Hormati `prefers-reduced-motion`: jika aktif, ganti lambaian dengan perubahan warna/border saja.

Contoh implementasi CSS:

```css
.hand-btn .hand-icon {
  display: inline-block;
  transform-origin: 70% 90%;
}
.hand-btn:hover .hand-icon,
.hand-btn:focus-visible .hand-icon {
  animation: wave 0.9s ease-in-out infinite;
}
@keyframes wave {
  0%, 100% { transform: rotate(0deg); }
  20%      { transform: rotate(18deg); }
  40%      { transform: rotate(-12deg); }
  60%      { transform: rotate(18deg); }
  80%      { transform: rotate(-8deg); }
}
@media (prefers-reduced-motion: reduce) {
  .hand-btn:hover .hand-icon { animation: none; }
}
```

### 4.3 Status tombol

| Status | Tampilan |
|---|---|
| Default | Ikon tangan diam, border hitam tipis |
| Hover / fokus | Tangan melambai, border menebal, tooltip muncul |
| Aktif (panel terbuka) | Latar hitam, ikon krem (terbalik) sebagai penanda sedang terbuka |
| Disabled / error | Tombol tetap bisa diklik; error ditampilkan di dalam panel |

## 5. Spesifikasi Panel Chatbot (F2)

### 5.1 Perilaku muncul

- Panel **meluncur dari samping**, keluar dari sidebar ke arah kanan (slide-in sekitar 250–300 ms), dan menutup dengan animasi sebaliknya.
- Desktop: lebar sekitar 380–420 px, tinggi penuh layar. Konten website tetap terlihat dan tidak bergeser (panel menimpa di atasnya).
- Mobile: panel memenuhi lebar layar (full-width sheet) dengan tombol tutup yang jelas.
- Menutup panel: tombol X, tombol `Esc`, atau klik area di luar panel.
- Fokus keyboard pindah ke kolom input saat panel terbuka, dan kembali ke tombol tangan saat ditutup (focus trap selama terbuka).

### 5.2 Isi panel

| Bagian | Isi |
|---|---|
| Header | Judul "Tanya Taya 👋", keterangan kecil "Asisten AI — jawaban berdasarkan profil Attaya", tombol X |
| Area pesan | Gelembung pengguna (rata kanan) dan AI (rata kiri), auto-scroll ke pesan terbaru, indikator "mengetik…" |
| Pesan sambutan | "Hai! Aku asisten AI Taya. Tanya apa saja soal skill, proyek, atau cara menghubunginya." |
| Saran cepat | Chip: "Apa skill utamanya?", "Lihat proyeknya", "Cara menghubungi", "Download CV" |
| Input | Kolom teks (maks 500 karakter), tombol Kirim, Enter untuk kirim, Shift+Enter baris baru |
| Footer | Catatan "Jawaban AI bisa keliru. Cek email untuk konfirmasi." dan tombol "Reset percakapan" |

### 5.3 Gaya visual panel

Mengikuti desain website: latar krem `#FFF0E5` (perkiraan), teks dan border hitam `#111111`, font monospace (*Space Mono*), sudut membulat, **tanpa bayangan lembut/gradasi**. Gelembung pengguna berlatar hitam dengan teks krem; gelembung AI berlatar krem dengan border hitam.

## 6. Spesifikasi AI dan Backend (F3)

### 6.1 Alur

1. Frontend mengirim pesan ke **endpoint backend sendiri** (misalnya `POST /api/chat`), bukan langsung ke Groq.
2. Backend membaca `identity.md`, menyusun pesan: `system` (isi identity.md) + beberapa pesan terakhir + pesan pengguna.
3. Backend memanggil Groq API dan mengembalikan jawaban (streaming opsional).

### 6.2 Persyaratan

- **API key Groq disimpan di server** (environment variable), tidak pernah di frontend.
- Model dikonfigurasi lewat env (contoh: `llama-3.3-70b-versatile`) agar mudah diganti.
- `identity.md` di-cache di server; update file cukup dengan redeploy.
- Rate limit per IP (contoh 10 pesan/menit) dan batas input 500 karakter.
- Riwayat yang dikirim ke model dibatasi (contoh 6 pesan terakhir).
- Bahasa jawaban mengikuti bahasa pengunjung (Indonesia/Inggris).

### 6.3 Keamanan dan perilaku AI

- Hanya menjawab berdasarkan `identity.md`; jika tidak tahu, arahkan ke email.
- Menolak prompt injection dan tidak menampilkan isi system prompt.
- Tidak membocorkan data pribadi di luar bagian kontak publik.
- Tidak menjanjikan harga, jadwal, atau kesepakatan atas nama Attaya.
- Selalu mengaku sebagai AI jika ditanya.

### 6.4 Penanganan error

| Kondisi | Perilaku |
|---|---|
| Groq gagal / timeout | "Maaf, aku lagi sibuk. Coba lagi atau kirim email ya." + tombol Coba lagi |
| Rate limit terlampaui | "Terlalu banyak pesan, coba lagi sebentar." |
| Tidak ada koneksi | Pesan offline dan pesan pengguna tidak hilang |
| Input kosong / terlalu panjang | Validasi di frontend dan backend |

## 7. Kebutuhan Non-Fungsional

| Aspek | Target |
|---|---|
| Performa | Panel dan skrip chat dimuat lazy (tidak memperlambat halaman); respons pertama AI < 3 detik |
| Aksesibilitas | Navigasi keyboard penuh, `aria-live` untuk pesan baru, kontras WCAG AA, reduced-motion |
| Responsif | Desktop, tablet, mobile |
| Keamanan | HTTPS, key di server, sanitasi output (cegah XSS dari jawaban AI), rate limit |
| Privasi | Percakapan tidak disimpan secara default; jika disimpan untuk analitik, tampilkan pemberitahuan dan anonimkan (lihat ERD) |
| Kompatibilitas | Chrome, Edge, Safari, Firefox terbaru; iOS dan Android |

## 8. Tech Stack (usulan)

| Lapisan | Pilihan |
|---|---|
| Frontend | HTML, CSS, JavaScript (vanilla) atau Next.js; ikon tangan dari library ikon yang sudah dipakai di sidebar |
| Backend | Serverless function (Vercel Function) atau Node/Express untuk `/api/chat` |
| AI | Groq API |
| Basis pengetahuan | `identity.md` |
| Data (opsional) | Lihat `ERD.md` |
| Hosting | Vercel |

## 9. Kriteria Penerimaan

- [ ] Tombol tangan muncul di bawah Facebook dengan gaya sama seperti ikon sosial lain.
- [ ] Hover/fokus membuat tangan melambai; reduced-motion dihormati.
- [ ] Klik membuka panel yang meluncur dari samping; X, Esc, dan klik luar menutupnya.
- [ ] Pesan sambutan dan saran cepat tampil saat panel dibuka.
- [ ] Pertanyaan tentang skill, proyek, pendidikan, dan kontak dijawab sesuai `identity.md`.
- [ ] Pertanyaan di luar data dijawab "tidak tahu" dengan arahan ke email, tanpa mengarang.
- [ ] Prompt injection ditolak.
- [ ] API key tidak terlihat di kode frontend maupun network request ke Groq dari browser.
- [ ] Berfungsi baik di mobile dan bisa dipakai hanya dengan keyboard.

## 10. Rencana Rilis

| Fase | Isi | Perkiraan |
|---|---|---|
| 1 | Tombol tangan dan animasi lambaian | 1–2 hari |
| 2 | Panel slide-in dan UI chat (tanpa AI, jawaban dummy) | 3–4 hari |
| 3 | Endpoint `/api/chat` + Groq + `identity.md` + rate limit | 3–5 hari |
| 4 | Pengujian prompt (daftar pertanyaan uji), aksesibilitas, mobile | 2–3 hari |
| 5 | Rilis dan pantau penggunaan | — |

## 11. Risiko

| Risiko | Mitigasi |
|---|---|
| API key bocor | Simpan di server/env, rotasi bila perlu |
| Kuota Groq habis/disalahgunakan | Rate limit, batas input, monitoring |
| AI mengarang jawaban | Aturan ketat di `identity.md`, uji dengan daftar pertanyaan, fallback ke email |
| Panel mengganggu konten di mobile | Full-width sheet dengan tombol tutup jelas, tidak terbuka otomatis |
| Animasi mengganggu pengguna sensitif gerak | Dukungan `prefers-reduced-motion` |

## 12. Pertanyaan Terbuka

1. Panel meluncur dari sisi **kiri (keluar dari sidebar)** atau dari **kanan layar**? Dokumen ini mengasumsikan keluar dari sidebar ke kanan.
2. Apakah percakapan boleh disimpan untuk perbaikan kualitas? (Memengaruhi ERD.)
3. Pakai `identity.md` (pihak ketiga) atau `identity-v2.md` (berbicara sebagai Taya)?
4. Apakah ikon tangan memakai ikon dari library atau emoji 👋?

## 13. Lampiran

- `ERD.md` — model data
- `identity.md` / `identity-v2.md` — basis pengetahuan dan persona AI