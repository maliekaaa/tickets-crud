# 🔐 Lab Authentication & Password Security

**Minggu 4 - Hari 1: Authentication & Password Security**

> "Who Are You? Prove It!"

## 📋 Ringkasan Lab

Lab ini mendemonstrasikan implementasi **authentication** yang aman vs rentan di Laravel, 
dengan fokus pada:

- **Password Hashing** (bcrypt vs plaintext/md5)
- **Rate Limiting** (mencegah brute force)
- **Session Security** (konfigurasi session yang aman)
- **Password Validation** (aturan password kuat)

## 🎯 Tujuan Pembelajaran

| # | Tujuan |
|---|--------|
| 1 | Memahami perbedaan authentication vs authorization |
| 2 | Mengimplementasikan login/register dengan Laravel Breeze |
| 3 | Memahami password hashing (bcrypt, Argon2) |
| 4 | Menerapkan rate limiting untuk mencegah brute force |
| 5 | Mengamankan konfigurasi session |

## 📁 Struktur Lab

```
hari-1-authentication/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Secure controllers
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   └── VulnerableAuth/          # Vulnerable controllers (LAB)
│   │   │       ├── VulnerableLoginController.php
│   │   │       └── VulnerableRegisterController.php
│   │   ├── Middleware/
│   │   │   └── ThrottleLogins.php
│   │   └── Requests/
│   │       └── Auth/
│   │           └── LoginRequest.php
│   └── Models/
│       ├── User.php                     # Secure user model
│       └── VulnerableUser.php           # Vulnerable user model (LAB)
├── database/
│   └── migrations/
│       └── create_users_tables.php
├── resources/
│   └── views/
│       ├── auth/                        # Secure auth views
│       ├── vulnerable-auth/             # Vulnerable auth views (LAB)
│       ├── comparison/                  # Comparison page
│       └── layouts/
├── routes/
│   └── web.php
└── README.md
```

## 🔴 VULNERABLE vs 🟢 SECURE

### 1. Password Storage

| Aspek | 🔴 Vulnerable | 🟢 Secure |
|-------|---------------|-----------|
| Storage | Plain text / MD5 | bcrypt / Argon2 |
| Code | `$user->password = $password` | `Hash::make($password)` |
| Risk | Password langsung terbaca jika DB bocor | Hash tidak bisa di-reverse |

### 2. Rate Limiting

| Aspek | 🔴 Vulnerable | 🟢 Secure |
|-------|---------------|-----------|
| Limiting | Tidak ada | 5 attempts / minute |
| Code | Langsung cek password | RateLimiter + throttle |
| Risk | Brute force ribuan kali/detik | Brute force tidak praktis |

### 3. Session Security

| Aspek | 🔴 Vulnerable | 🟢 Secure |
|-------|---------------|-----------|
| Regenerate | Tidak | Ya, setelah login |
| HTTP Only | Tidak | Ya |
| Secure Cookie | Tidak | Ya (HTTPS) |

### 4. Password Validation

| Aspek | 🔴 Vulnerable | 🟢 Secure |
|-------|---------------|-----------|
| Min Length | Tidak ada | 8 karakter |
| Complexity | Tidak ada | Letters + Numbers |
| Check | Tidak ada | Breached passwords |

## 🧪 Skenario Lab

### Lab 1: Brute Force Attack (10 menit)

1. Buka `/vulnerable/login`
2. Coba login berkali-kali dengan password salah
3. Perhatikan: **Tidak ada pembatasan!**
4. Bandingkan dengan `/login` → Setelah 5x gagal, terkunci 1 menit

### Lab 2: Password Storage (10 menit)

1. Register di `/vulnerable/register` dengan password "test123"
2. Lihat di database: password tersimpan **plain text**
3. Register di `/register` dengan password yang sama
4. Lihat di database: password ter-**hash** dengan bcrypt

### Lab 3: Session Hijacking Prevention (10 menit)

1. Login di `/vulnerable/login`
2. Cek cookie di DevTools → Session ID tidak berubah
3. Login di `/login`
4. Cek cookie → Session ID **ter-regenerate** setelah login

### Lab 4: Weak Password (10 menit)

1. Register di `/vulnerable/register` dengan password "123"
2. **Berhasil!** (tidak ada validasi)
3. Register di `/register` dengan password "123"
4. **Error!** Password harus minimal 8 karakter

## 🛡️ OWASP Reference

**A07:2021 - Identification and Authentication Failures**

Vulnerability yang termasuk:
- Weak passwords allowed
- Credential stuffing / brute force
- Missing MFA
- Session ID exposed in URL
- Session not invalidated after logout
- Plain text password storage

## 📚 Referensi

- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [Laravel Breeze Documentation](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
- [Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

---

**⚠️ PERINGATAN:** Kode vulnerable di lab ini **HANYA untuk pembelajaran**. 
Jangan gunakan di production!

*Secure Coding Bootcamp - SMK Wikrama Bogor © 2026*
