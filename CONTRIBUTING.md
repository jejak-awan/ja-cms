# Contributing Guide

## 1. Struktur & Penamaan
- Gunakan `app/Modules/` untuk setiap fitur utama (modular).
- Views disimpan di `resources/views/fitur`.
- Penamaan file dan folder:
  - Folder: PascalCase (`Article`, `User`)
  - File class: PascalCase (`ArticleController.php`)
  - View: kebab-case (`index.blade.php`)
  - Migration: `YYYY_MM_DD_HHMMSS_create_table_name.php`
  - Seeder: PascalCase (`ArticleSeeder.php`)
  - Config: snake_case (`cms_settings.php`)

## 2. Dokumentasi & Komentar
- Setiap fungsi/class wajib PHPDoc.
- Dokumentasi API dan fitur di README/Wiki.
- Komentar hanya untuk logika kompleks.

## 3. Git Workflow
- Gunakan git flow: main/master, develop, feature/bugfix.
- Semua perubahan lewat pull request dan code review.
- Commit message jelas: `[feature]`, `[fix]`, `[refactor]`, `[docs]`.

## 4. Testing & Quality
- Unit test wajib untuk business logic dan API.
- Gunakan PHPUnit dan Laravel test suite.
- Coverage minimal 80% untuk modul utama.

## 5. Dependency & Security
- Hanya gunakan package/library yang aktif dan termaintain.
- Validasi dan sanitasi semua input user.
- Gunakan .env untuk credential dan konfigurasi sensitif.

## 6. Style Frontend
- Gunakan Tailwind CSS atau Bootstrap.
- Komponen UI reusable dan modular.
- Responsive design wajib.

## 7. API & Integrasi
- API mengikuti standar RESTful.
- Semua endpoint harus ada dokumentasi dan test.
- Gunakan resource/transformer untuk response API.

## 8. Deployment & CI/CD
- Semua perubahan harus bisa di-deploy otomatis (CI/CD pipeline).
- Backup dan rollback harus mudah dilakukan.
- Monitoring error/log terintegrasi.

## 9. Aturan AI Agent
- AI agent hanya boleh mengedit file sesuai standar dan workflow di atas.
- AI agent wajib menulis log perubahan dan alasan di commit/pull request.
- AI agent harus mematuhi aturan validasi, testing, dan dokumentasi.
- AI agent dilarang mengubah credential, konfigurasi sensitif, atau data production tanpa persetujuan.

## 10. Komunikasi & Kolaborasi
- Semua diskusi dan keputusan teknis dicatat di issue tracker atau Wiki.
- Setiap task harus jelas, terukur, dan punya acceptance criteria.
