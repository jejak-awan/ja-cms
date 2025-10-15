<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Language Lines (Indonesian)
    |--------------------------------------------------------------------------
    */

    // Sidebar Navigation
    'nav' => [
        'dashboard' => 'Dashboard',
        'content' => 'Konten',
        'articles' => 'Artikel',
        'categories' => 'Kategori',
        'tags' => 'Tag',
        'pages' => 'Halaman',
        'media' => 'Media',
        'appearance' => 'Tampilan',
        'themes' => 'Tema',
        'menus' => 'Menu',
        'users' => 'Pengguna',
        'roles' => 'Peran',
        'permissions' => 'Izin',
        'settings' => 'Pengaturan',
        'seo' => 'SEO',
        'plugins' => 'Plugin',
        'view_site' => 'Lihat Situs',
    ],

    // Language Switcher
    'switch_language' => 'Ganti Bahasa',
    'current_locale' => 'Bahasa Saat Ini',
    'select_language' => 'Pilih Bahasa',
    'language_switched' => 'Bahasa berhasil diganti ke :locale :flag',

    // Language Settings
    'language_settings' => 'Pengaturan Bahasa',
    'language_settings_description' => 'Konfigurasi preferensi bahasa dan deteksi browser',
    'language_configuration' => 'Konfigurasi Bahasa',
    'default_language' => 'Bahasa Default',
    'default_language_help' => 'Bahasa default untuk pengguna baru dan konten',
    'active_languages' => 'Bahasa Aktif',
    'active_languages_help' => 'Bahasa yang tersedia untuk dipilih pengguna',
    'language_order' => 'Urutan Bahasa',
    'language_order_help' => 'Seret untuk mengatur ulang bahasa di switcher',
    'language_statistics' => 'Statistik Bahasa',
    'total_languages' => 'Total Bahasa',
    'browser_detection' => 'Deteksi Browser',
    'browser_detection_help' => 'Otomatis deteksi bahasa pengguna dari browser',
    'enabled' => 'Diaktifkan',
    'disabled' => 'Dinonaktifkan',
    'quick_actions' => 'Aksi Cepat',
    'activate_all' => 'Aktifkan Semua',
    'deactivate_all' => 'Nonaktifkan Semua',
    'clear_cache' => 'Hapus Cache',
    'language_management' => 'Manajemen Bahasa',
    'language_management_description' => 'Kelola bahasa situs, atur default, dan konfigurasi deteksi browser',
    'manage_languages' => 'Kelola Bahasa',
    'clear_language_cache' => 'Bersihkan Cache Bahasa',
    'view_statistics' => 'Lihat Statistik',
    'enable_browser_detection' => 'Aktifkan deteksi bahasa browser',
    'browser_detection_description' => 'Otomatis deteksi bahasa pengguna dari pengaturan browser',
    'error_occurred' => 'Terjadi kesalahan',
    'confirm_clear_cache' => 'Apakah Anda yakin ingin membersihkan cache bahasa?',
    'save_settings' => 'Simpan Pengaturan',
    'reset' => 'Reset',
    'confirm_reset' => 'Apakah Anda yakin ingin mereset semua pengaturan?',
    'language_settings_updated' => 'Pengaturan bahasa berhasil diperbarui',
    'language_activated' => 'Bahasa diaktifkan',
    'language_deactivated' => 'Bahasa dinonaktifkan',
    'default_language_updated' => 'Bahasa default diperbarui',
    'language_order_updated' => 'Urutan bahasa diperbarui',
    'language_cache_cleared' => 'Cache bahasa berhasil dihapus',
    'usage_statistics' => 'Statistik Penggunaan',
    'language_distribution' => 'Distribusi Bahasa',
    'no_default_set' => 'Tidak ada bahasa default yang ditetapkan',
    'active' => 'Aktif',
    'inactive' => 'Tidak Aktif',
    'default' => 'Default',
    'loading' => 'Memuat',
    'statistics' => 'Statistik',

    // Dashboard
    'dashboard' => [
        'welcome' => 'Selamat datang kembali, :name!',
        'subtitle' => 'Inilah yang terjadi dengan situs Anda hari ini.',
        'last_updated' => 'Terakhir diperbarui',
        'overview' => 'Ringkasan',
        'quick_stats' => 'Statistik Cepat',
        'recent_activity' => 'Aktivitas Terbaru',
        'system_info' => 'Informasi Sistem',
    ],

    // Articles
    'articles' => [
        'title' => 'Artikel',
        'create' => 'Buat Artikel',
        'edit' => 'Edit Artikel',
        'list' => 'Daftar Artikel',
        'all_articles' => 'Semua Artikel',
        'published_articles' => 'Artikel Dipublikasikan',
        'draft_articles' => 'Artikel Draft',
        'title_label' => 'Judul Artikel',
        'content_label' => 'Konten',
        'excerpt_label' => 'Ringkasan',
        'category_label' => 'Kategori',
        'tags_label' => 'Tag',
        'featured_image' => 'Gambar Unggulan',
        'author' => 'Penulis',
        'status' => 'Status',
        'publish' => 'Publikasikan',
        'save_draft' => 'Simpan Draft',
        'schedule' => 'Jadwalkan',
        'views' => 'Dilihat',
        'featured' => 'Unggulan',
    ],

    // Categories
    'categories' => [
        'title' => 'Kategori',
        'create' => 'Buat Kategori',
        'edit' => 'Edit Kategori',
        'list' => 'Daftar Kategori',
        'name_label' => 'Nama Kategori',
        'slug_label' => 'Slug',
        'description_label' => 'Deskripsi',
        'parent_label' => 'Kategori Induk',
        'order_label' => 'Urutan',
        'is_active' => 'Aktif',
    ],

    // Pages
    'pages' => [
        'title' => 'Halaman',
        'create' => 'Buat Halaman',
        'edit' => 'Edit Halaman',
        'list' => 'Daftar Halaman',
        'title_label' => 'Judul Halaman',
        'content_label' => 'Konten',
        'template_label' => 'Template',
        'parent_label' => 'Halaman Induk',
        'order_label' => 'Urutan',
    ],

    // Media
    'media' => [
        'title' => 'Media',
        'library' => 'Pustaka Media',
        'upload' => 'Unggah Media',
        'select' => 'Pilih Media',
        'insert' => 'Sisipkan',
        'file_name' => 'Nama File',
        'file_type' => 'Tipe File',
        'file_size' => 'Ukuran File',
        'uploaded_by' => 'Diunggah oleh',
        'alt_text' => 'Teks Alternatif',
    ],

    // Menus
    'menus' => [
        'title' => 'Menu',
        'create' => 'Buat Menu',
        'edit' => 'Edit Menu',
        'manage' => 'Kelola Menu',
        'name_label' => 'Nama Menu',
        'location_label' => 'Lokasi',
        'add_item' => 'Tambah Item',
        'menu_structure' => 'Struktur Menu',
        'menu_items' => 'Item Menu',
    ],

    // Users
    'users' => [
        'title' => 'Pengguna',
        'create' => 'Buat Pengguna',
        'edit' => 'Edit Pengguna',
        'list' => 'Daftar Pengguna',
        'name_label' => 'Nama',
        'email_label' => 'Email',
        'password_label' => 'Password',
        'role_label' => 'Peran',
        'avatar' => 'Avatar',
        'bio' => 'Bio',
        'last_login' => 'Login Terakhir',
    ],

    // Settings
    'settings' => [
        'title' => 'Pengaturan',
        'general' => 'Umum',
        'site_name' => 'Nama Situs',
        'site_description' => 'Deskripsi Situs',
        'site_logo' => 'Logo Situs',
        'timezone' => 'Zona Waktu',
        'language' => 'Bahasa',
        'date_format' => 'Format Tanggal',
        'time_format' => 'Format Waktu',
        'cache' => 'Cache',
        'clear_cache' => 'Bersihkan Cache',
        'maintenance_mode' => 'Mode Pemeliharaan',
    ],

    // Dashboard
    'dashboard' => [
        'title' => 'Dashboard',
        'welcome_back' => 'Selamat datang kembali',
        'overview' => 'Ringkasan',
        'statistics' => 'Statistik',
        'recent_articles' => 'Artikel Terbaru',
        'recent_activity' => 'Aktivitas Terbaru',
        'quick_actions' => 'Aksi Cepat',
        'total_articles' => 'Total Artikel',
        'total_pages' => 'Total Halaman',
        'total_users' => 'Total Pengguna',
        'total_views' => 'Total Dilihat',
    ],

];
