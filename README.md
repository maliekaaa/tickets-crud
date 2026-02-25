# 📁 Lab Minggu 3 - Hari 2: Input Validation

## Struktur File

```
hari-2-input-validation/
├── app/
│   └── Http/
│       └── Requests/
│           ├── StoreTicketRequest.php    # Form Request untuk create
│           └── UpdateTicketRequest.php   # Form Request untuk update
│
├── resources/views/
│   ├── tickets/
│   │   ├── create.blade.php              # Form create dengan validasi
│   │   └── edit.blade.php                # Form edit dengan validasi
│   │
│   └── validation-lab/
│       ├── index.blade.php               # Menu lab validasi
│       ├── vulnerable.blade.php          # Demo TANPA server-side validation
│       └── secure.blade.php              # Demo DENGAN server-side validation
│
├── app/Http/Controllers/
│   └── ValidationLabController.php       # Controller untuk demo lab
│
├── routes/
│   └── web.php                           # Routes untuk lab
│
├── README.md
└── PANDUAN-IMPLEMENTASI.md
```

## Konsep Utama

### 🔐 Never Trust User Input

Semua input dari user harus dianggap **BERBAHAYA** sampai divalidasi!

### 📊 Defense in Depth

| Layer | Lokasi | Tujuan | Keamanan? |
|-------|--------|--------|-----------|
| 1 | Client-side (JS/HTML5) | UX - feedback cepat | ❌ Bisa di-bypass |
| 2 | Server-side (Laravel) | Validasi UTAMA | ✅ WAJIB |
| 3 | Database (Constraints) | Last defense | ✅ Backup |

### 🎯 Tipe Validasi

1. **Type Validation** - string, integer, array, file
2. **Format Validation** - email, URL, date, regex
3. **Range Validation** - min, max, between
4. **Length Validation** - min, max, size
5. **Business Rule** - unique, exists, confirmed

## Cara Implementasi

1. Copy semua file ke project Laravel
2. Register routes di `web.php`
3. Akses `/validation-lab` untuk demo
4. Implementasi Form Request di ticket CRUD

## ⚠️ Penting

- Client-side validation = UX, **BUKAN** keamanan!
- Server-side validation = **WAJIB** untuk keamanan!
- Selalu gunakan `$request->validated()` untuk data yang sudah bersih
