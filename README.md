# JA-CMS

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.33-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Modern Content Management System Built with Laravel 12**

[Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation) • [License](#-license)

</div>

---

## 📋 About

**JA-CMS (Jejakawan Content Management System)** adalah sistem manajemen konten yang powerful dan fleksibel, dibangun di atas Laravel 12. Dirancang dengan arsitektur modular yang memudahkan pengembangan dan ekstensi fitur.

- **Version**: 1.0.0
- **Code Build**: Janari
- **Author**: Jejakawan Team
- **Repository**: [jejak-awan/ja-cms](https://github.com/jejak-awan/ja-cms)

---

## ✨ Features

### 🎨 Content Management
- **📝 Articles & Pages** - Rich text editor dengan TinyMCE
- **🗂️ Categories** - Sistem kategori hierarki dengan drag-and-drop
- **🏷️ Tags** - Flexible tagging system
- **🔍 SEO Optimization** - Meta tags, sitemap, dan breadcrumbs

### 📁 Media Management
- **📤 Drag & Drop Upload** - Interface upload yang modern
- **🖼️ Image Processing** - Automatic thumbnail generation (Intervention Image v3)
- **📂 Folder Organization** - Organize media dalam folder custom

### 👥 User Management
- **🔐 Role-Based Access Control** - 4 roles (Super Admin, Admin, Editor, Author)
- **🛡️ Permissions System** - 24 granular permissions
- **📊 User Dashboard** - Individual statistics dan activity tracking

### 🎨 Theme & Customization
- **🎭 Dual Theme Support** - Tema terpisah untuk Admin Panel dan Public Website
- **🔌 Plugin Architecture** - Extend functionality via plugins
- **🎯 Menu Builder** - Drag & drop menu management (3 locations)
- **⚙️ Settings System** - Global configuration dengan type casting dan caching

### 🌟 Modern Interface
- **📱 Responsive Design** - Mobile-first admin interface
- **🌙 Dark Mode** - Full dark mode support
- **🔔 Notifications** - Real-time notification system

---

## 🚀 Installation

### Requirements

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- Database (SQLite/MySQL/PostgreSQL)

### Quick Start

```bash
# Clone repository
git clone https://github.com/jejak-awan/ja-cms.git
cd ja-cms

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=sqlite

# Run migrations
php artisan migrate --seed

# Build assets
npm run build

# Create storage link
php artisan storage:link

# Start server
php artisan serve
```

### Access Application

- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Sitemap**: http://localhost:8000/sitemap.xml

Default admin credentials dibuat saat seeding (check database seeders).

---

## 💻 Usage

### Managing Content

1. Login ke admin panel
2. Navigate ke **Articles** atau **Pages**
3. Click **Create New** untuk membuat konten baru
4. Gunakan rich text editor untuk menulis konten
5. Set SEO options dan publish

### Managing Themes

1. Go to **Admin → Themes**
2. Browse available themes (Admin/Public)
3. Click **Activate** untuk apply theme
4. Configure theme settings sesuai kebutuhan

### Building Menus

1. Go to **Admin → Menus**
2. Create menu baru atau edit yang existing
3. Add items (Pages, Categories, Custom URLs)
4. Drag untuk reorder dan nest items
5. Assign menu ke location

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12.33, PHP 8.3
- **Frontend**: Tailwind CSS 4.0, Alpine.js, Vite
- **Database**: SQLite/MySQL/PostgreSQL
- **Editor**: TinyMCE
- **Image Processing**: Intervention Image v3

---

## 📚 Documentation

Dokumentasi lengkap tersedia di folder [`docs/`](docs/):

- **[📖 Project Completion Report](docs/PROJECT_COMPLETION_REPORT.md)** - Full project report
- **[📑 Documentation Index](docs/INDEX.md)** - Quick navigation
- **[🎨 Theme System](docs/THEME_SYSTEM.md)** - Multi-theme architecture
- **[🎨 Theme Guide](docs/THEME_GUIDE.md)** - Theme development guide
- **[📝 Quick Start Guide](docs/QUICK_START_GUIDE.md)** - Getting started

---

## 📁 Project Structure

```
cms-app/
├── app/
│   ├── Http/Controllers/
│   ├── Modules/              # Modular architecture
│   │   ├── Article/
│   │   ├── Category/
│   │   ├── Page/
│   │   ├── Media/
│   │   ├── User/
│   │   └── ...
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── docs/                     # 📚 Documentation
├── public/
│   └── themes/
│       ├── admin/            # Admin themes
│       └── public/           # Public themes
├── resources/
│   └── views/
└── tests/
```

---

## 🧪 Testing

```bash
# Run PHPUnit tests
php artisan test

# Run manual tests
php tests/Manual/test_final_complete.php
```

---

## 🚀 Deployment

### Production Setup

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
```

---

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 📞 Contact

- **Email**: jejakawan007@gmail.com
- **GitHub**: [jejak-awan](https://github.com/jejak-awan)
- **Issues**: [Report Issues](https://github.com/jejak-awan/ja-cms/issues)

---

<div align="center">

**⭐ Star us on GitHub if you find this helpful!**

Made with ❤️ by [Jejakawan Team](https://github.com/jejak-awan)

</div>
