# ERD — Portofolio Attaya Arkarna + Chatbot Tangan Melambai

Dokumen ini menggambarkan model data untuk website portofolio dan fitur chatbot. Diagram memakai sintaks **Mermaid** (tampil otomatis di GitHub, VS Code dengan ekstensi Mermaid, dan Notion).

> **Catatan privasi:** tabel `chat_session` dan `chat_message` bersifat **opsional**. Secara default percakapan tidak perlu disimpan. Aktifkan hanya jika Attaya ingin menganalisis kualitas jawaban, dan beri tahu pengunjung.

---

## 1. Diagram

```mermaid
erDiagram
    PROFILE ||--o{ SOCIAL_LINK : has
    PROFILE ||--o{ SKILL : has
    PROFILE ||--o{ EDUCATION : has
    PROFILE ||--o{ EXPERIENCE : has
    PROFILE ||--o{ PROJECT : owns
    PROFILE ||--o{ STAT : shows
    PROFILE ||--o{ KNOWLEDGE_DOC : "feeds chatbot"

    PROJECT ||--o{ PROJECT_TECH : uses
    TECH ||--o{ PROJECT_TECH : "used in"
    PROJECT ||--o{ PROJECT_IMAGE : has

    CHAT_SESSION ||--o{ CHAT_MESSAGE : contains
    CHAT_SESSION ||--o{ FEEDBACK : receives
    KNOWLEDGE_DOC ||--o{ CHAT_MESSAGE : "grounds answers"

    VISITOR_EVENT }o--o| CHAT_SESSION : "may relate to"
    CONTACT_MESSAGE }o--o| CHAT_SESSION : "may come from"

    PROFILE {
        int id PK
        string full_name
        string nickname
        string headline
        text bio
        string school
        string location
        string email
        string phone
        string cv_url
        string photo_url
        string response_hours
    }

    SOCIAL_LINK {
        int id PK
        int profile_id FK
        string platform
        string url
        int sort_order
        boolean is_active
    }

    SKILL {
        int id PK
        int profile_id FK
        string name
        string category
        int level_percent
        boolean show_in_bar
    }

    EDUCATION {
        int id PK
        int profile_id FK
        string institution
        string program
        int year_start
        int year_end
        string logo_url
    }

    EXPERIENCE {
        int id PK
        int profile_id FK
        string title
        string role_label
        int year_start
        int year_end
        text description
    }

    STAT {
        int id PK
        int profile_id FK
        string label
        string value
        int sort_order
    }

    PROJECT {
        int id PK
        int profile_id FK
        string title
        string type
        text description
        string demo_url
        string repo_url
        string cover_image_url
        boolean is_featured
        int sort_order
    }

    TECH {
        int id PK
        string name
    }

    PROJECT_TECH {
        int project_id FK
        int tech_id FK
    }

    PROJECT_IMAGE {
        int id PK
        int project_id FK
        string image_url
        string caption
        int sort_order
    }

    KNOWLEDGE_DOC {
        int id PK
        int profile_id FK
        string file_name
        string version
        text content
        datetime updated_at
        boolean is_active
    }

    CHAT_SESSION {
        uuid id PK
        string anon_visitor_id
        string ip_hash
        string language
        string device_type
        datetime started_at
        datetime last_active_at
    }

    CHAT_MESSAGE {
        int id PK
        uuid session_id FK
        int knowledge_doc_id FK
        string role
        text content
        string model
        int tokens_in
        int tokens_out
        int latency_ms
        boolean was_unanswered
        datetime created_at
    }

    FEEDBACK {
        int id PK
        uuid session_id FK
        int message_id
        string rating
        string comment
        datetime created_at
    }

    CONTACT_MESSAGE {
        int id PK
        uuid session_id FK
        string sender_name
        string sender_email
        text message
        string status
        datetime created_at
    }

    VISITOR_EVENT {
        int id PK
        uuid session_id FK
        string event_type
        string target
        datetime created_at
    }

    RATE_LIMIT {
        string key PK
        int hit_count
        datetime window_start
    }
```

---

## 2. Penjelasan Entitas

| Entitas | Fungsi | Catatan |
|---|---|---|
| `PROFILE` | Data utama Attaya (nama, bio, kontak, CV) | Satu baris saja (satu pemilik) |
| `SOCIAL_LINK` | Link Instagram, LinkedIn, GitHub, Facebook | **Satu sumber data** untuk header, hero, dan footer agar tidak ada URL ganda |
| `SKILL` | Daftar skill dan level persen | `show_in_bar` menentukan skill tampil di progress bar atau hanya di daftar "Mastered Skills" |
| `EDUCATION` | Riwayat sekolah | Perlu dirapikan tahunnya (lihat PRD) |
| `EXPERIENCE` | Pengalaman (UI Design, Web Developing) | |
| `STAT` | Statistik di About Me (10+, 80%, 89%) | |
| `PROJECT` | Proyek (Meowketplace, Sgarage, Eyfy) | `type`: website / figma |
| `TECH`, `PROJECT_TECH` | Teknologi per proyek (relasi many-to-many) | |
| `PROJECT_IMAGE` | Galeri gambar proyek | |
| `KNOWLEDGE_DOC` | Menyimpan isi `identity.md` dan versinya | Bisa juga cukup dibaca dari file; tabel ini hanya perlu jika ingin versi dan riwayat |
| `CHAT_SESSION` | Satu sesi percakapan anonim | Simpan **hash** IP, bukan IP asli |
| `CHAT_MESSAGE` | Pesan pengguna dan AI | `was_unanswered` menandai pertanyaan yang AI tidak tahu, berguna untuk melengkapi `identity.md` |
| `FEEDBACK` | Jempol atas/bawah untuk jawaban | Opsional |
| `CONTACT_MESSAGE` | Pesan dari form "Message Me" | `status`: new / read / replied |
| `VISITOR_EVENT` | Klik tombol tangan, buka chat, download CV, klik kontak | Untuk analitik ringan |
| `RATE_LIMIT` | Penghitung pembatasan pesan per IP/sesi | Bisa disimpan di memori/Redis, tidak harus di database utama |

---

## 3. Relasi Utama

| Relasi | Kardinalitas | Arti |
|---|---|---|
| `PROFILE` – `SOCIAL_LINK` / `SKILL` / `EDUCATION` / `EXPERIENCE` / `PROJECT` / `STAT` | 1 : N | Satu profil punya banyak item |
| `PROJECT` – `TECH` (lewat `PROJECT_TECH`) | M : N | Satu proyek memakai banyak teknologi, satu teknologi dipakai banyak proyek |
| `CHAT_SESSION` – `CHAT_MESSAGE` | 1 : N | Satu sesi berisi banyak pesan |
| `KNOWLEDGE_DOC` – `CHAT_MESSAGE` | 1 : N | Versi pengetahuan yang dipakai saat menjawab |
| `CHAT_SESSION` – `CONTACT_MESSAGE` | 1 : 0..N | Pesan kontak bisa berasal dari sesi chat (misalnya setelah AI mengarahkan ke form) |

---

## 4. Alur Data Chatbot

```mermaid
sequenceDiagram
    participant V as Pengunjung
    participant F as Frontend (tombol tangan + panel)
    participant B as Backend /api/chat
    participant K as identity.md
    participant G as Groq API
    participant D as Database (opsional)

    V->>F: Hover tombol tangan (melambai)
    V->>F: Klik, panel meluncur dari samping
    F->>D: VISITOR_EVENT (open_chat)
    V->>F: Kirim pertanyaan
    F->>B: POST pesan
    B->>B: Cek RATE_LIMIT
    B->>K: Baca isi identity.md
    B->>G: system + riwayat + pertanyaan
    G-->>B: Jawaban
    B->>D: Simpan CHAT_MESSAGE (jika diaktifkan)
    B-->>F: Jawaban
    F-->>V: Tampilkan di panel
```

---

## 5. Opsi Implementasi

| Opsi | Cocok untuk | Catatan |
|---|---|---|
| **Tanpa database** | Versi awal yang paling sederhana | `PROFILE`, `SKILL`, `PROJECT`, dan lainnya disimpan sebagai satu file JSON; chat tidak disimpan |
| **Firebase Firestore** | Sudah ada di daftar skill Attaya | Koleksi per entitas; cocok untuk `CHAT_MESSAGE`, `CONTACT_MESSAGE`, `VISITOR_EVENT` |
| **Supabase / PostgreSQL** | Jika ingin relasi dan query yang rapi | Diagram di atas bisa langsung dijadikan skema SQL |

**Saran:** mulai tanpa database (JSON + `identity.md`), lalu tambahkan Firestore hanya untuk `CONTACT_MESSAGE` dan log chat anonim ketika dibutuhkan.

---

## 6. Aturan Data dan Privasi

- Jangan menyimpan IP asli; simpan hash (`ip_hash`).
- Jangan menyimpan data identitas pengunjung kecuali yang mereka isi sendiri di form kontak.
- Sediakan pemberitahuan singkat di panel chat jika percakapan dicatat.
- Batasi masa simpan log chat (misalnya 30–90 hari) lalu hapus otomatis.
- Pertanyaan dengan `was_unanswered = true` ditinjau berkala untuk melengkapi `identity.md`.