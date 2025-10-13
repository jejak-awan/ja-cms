# TinyMCE Local Installation Guide

## ✅ Installation Complete

TinyMCE telah berhasil diinstall secara **lokal** dan tidak lagi bergantung pada CDN eksternal.

---

## 📦 Yang Sudah Dilakukan

### 1. **Install TinyMCE via NPM** ✅
```bash
npm install tinymce --save
```

### 2. **Install Vite Plugin untuk Copy Assets** ✅
```bash
npm install vite-plugin-static-copy --save-dev
```

### 3. **Buat TinyMCE Configuration File** ✅
**File:** `resources/js/tinymce-config.js`

**Fitur:**
- Import semua plugins TinyMCE yang dibutuhkan
- Configuration function yang reusable
- Support image upload
- Auto-save functionality
- Code sample dengan syntax highlighting
- Mobile responsive
- Custom content styling

**Plugins yang diaktifkan:**
- advlist, anchor, autolink, autoresize, autosave
- charmap, code, codesample, directionality
- emoticons, fullscreen, help, image, importcss
- insertdatetime, link, lists, media, nonbreaking
- pagebreak, preview, quickbars, save, searchreplace
- table, visualblocks, visualchars, wordcount

### 4. **Update app.js** ✅
**File:** `resources/js/app.js`

Menambahkan import dan export TinyMCE functions sebagai global:
```javascript
window.initTinyMCE = initTinyMCE;
window.removeTinyMCE = removeTinyMCE;
window.getTinyMCEContent = getTinyMCEContent;
window.setTinyMCEContent = setTinyMCEContent;
```

### 5. **Update Vite Config** ✅
**File:** `vite.config.js`

Menambahkan plugin untuk copy TinyMCE assets ke public folder:
- skins
- icons
- themes
- models

### 6. **Update Blade Templates** ✅
**Files:**
- `resources/views/admin/articles/create.blade.php`
- `resources/views/admin/articles/edit.blade.php`

**Sebelum (CDN):**
```html
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({ selector: '#content', ... });
</script>
```

**Sesudah (Local):**
```javascript
<script>
if (typeof window.initTinyMCE === 'function') {
    window.initTinyMCE('#content', {
        height: 500,
        placeholder: 'Start writing your article content here...'
    });
}
</script>
```

### 7. **Build Assets** ✅
```bash
npm run build
```

**Hasil:**
- TinyMCE core: ~1.6 MB (minified)
- Build sukses dengan semua assets ter-copy

---

## 🎯 Keuntungan Local Installation

### 1. **Tidak Bergantung Internet** 🌐
- Tidak perlu koneksi ke `cdn.tiny.cloud`
- Aplikasi bisa berjalan offline
- Lebih reliable

### 2. **Performa Lebih Cepat** ⚡
- Load dari server lokal
- Tidak ada latency dari CDN eksternal
- Asset sudah di-minify dan optimize oleh Vite

### 3. **Keamanan Lebih Baik** 🔒
- Tidak ada third-party tracking
- Kontrol penuh atas kode
- Tidak ada dependency ke layanan eksternal

### 4. **Customization Penuh** 🎨
- Bisa modifikasi plugins sesuai kebutuhan
- Custom skin dan theme
- Tambah/hapus fitur dengan mudah

### 5. **Version Control** 📌
- Version terkunci di package.json
- Konsisten di semua environment
- Mudah rollback jika ada issue

---

## 🚀 Cara Penggunaan

### Basic Usage (Default Config)
```javascript
window.initTinyMCE('#content');
```

### With Custom Config
```javascript
window.initTinyMCE('#content', {
    height: 600,
    menubar: false,
    toolbar: 'bold italic | link image',
    placeholder: 'Type here...'
});
```

### Get Content
```javascript
const content = window.getTinyMCEContent('#content');
```

### Set Content
```javascript
window.setTinyMCEContent('#content', '<p>New content</p>');
```

### Remove Editor
```javascript
window.removeTinyMCE('#content');
```

---

## 📝 Available Functions

### `initTinyMCE(selector, customConfig)`
Initialize TinyMCE editor pada selector tertentu.

**Parameters:**
- `selector` (string) - CSS selector, default: `#content`
- `customConfig` (object) - Custom configuration options

**Example:**
```javascript
initTinyMCE('#my-editor', {
    height: 400,
    menubar: 'file edit view',
    plugins: 'link image code',
    toolbar: 'bold italic | link image | code'
});
```

### `getTinyMCEContent(selector)`
Ambil content dari editor.

**Returns:** String (HTML content)

### `setTinyMCEContent(selector, content)`
Set content ke editor.

**Parameters:**
- `selector` (string) - CSS selector
- `content` (string) - HTML content

### `removeTinyMCE(selector)`
Remove/destroy editor instance.

---

## 🔧 Configuration Options

### Default Configuration
```javascript
{
    height: 500,
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | blocks | bold italic forecolor | ...',
    autosave_interval: '30s',
    image_advtab: true,
    link_default_target: '_blank',
    content_style: '...',
    // ... dan banyak lagi
}
```

### Image Upload Configuration
Default sudah include handler untuk upload image via AJAX ke `/admin/upload-image`.

Untuk mengubah endpoint:
```javascript
initTinyMCE('#content', {
    images_upload_handler: function(blobInfo, progress) {
        // Custom upload logic
    }
});
```

---

## 📦 File Structure

```
cms-app/
├── node_modules/
│   └── tinymce/           # TinyMCE package
├── resources/
│   └── js/
│       ├── app.js         # Main app file (import TinyMCE)
│       └── tinymce-config.js  # TinyMCE configuration
├── public/
│   └── build/
│       ├── assets/        # Compiled JS & CSS
│       └── tinymce/       # TinyMCE assets (copied by Vite)
│           ├── skins/
│           ├── icons/
│           ├── themes/
│           └── models/
├── package.json           # tinymce dependency
└── vite.config.js         # Vite config with static-copy plugin
```

---

## 🔄 Development vs Production

### Development Mode
```bash
npm run dev
```
- Hot Module Replacement (HMR) enabled
- TinyMCE loaded via Vite dev server
- Fast rebuild on changes

### Production Mode
```bash
npm run build
```
- Assets minified & optimized
- TinyMCE bundled dengan app.js
- Ready for deployment

---

## ⚙️ Maintenance

### Update TinyMCE Version
```bash
npm update tinymce
npm run build
```

### Tambah Plugin Baru
Edit `resources/js/tinymce-config.js`:
```javascript
// Import plugin
import 'tinymce/plugins/newplugin';

// Add to config
plugins: [..., 'newplugin'],
```

Rebuild:
```bash
npm run build
```

---

## 🐛 Troubleshooting

### TinyMCE tidak muncul
1. Pastikan `npm run build` sudah dijalankan
2. Check browser console untuk error
3. Pastikan `@vite` directive ada di layout
4. Clear browser cache

### Skin tidak load
1. Check folder `public/build/tinymce/skins/` exists
2. Rerun `npm run build`
3. Check vite.config.js plugin configuration

### Image upload tidak jalan
1. Buat route `/admin/upload-image`
2. Implement controller untuk handle upload
3. Return JSON: `{ location: 'url/to/image.jpg' }`

---

## 📚 Resources

- **TinyMCE Documentation:** https://www.tiny.cloud/docs/
- **Vite Plugin Static Copy:** https://github.com/sapphi-red/vite-plugin-static-copy
- **Configuration Reference:** `resources/js/tinymce-config.js`

---

## ✅ Checklist

- [x] Install tinymce via npm
- [x] Install vite-plugin-static-copy
- [x] Create tinymce-config.js
- [x] Update app.js with imports
- [x] Update vite.config.js
- [x] Update blade templates (remove CDN)
- [x] Build assets
- [x] Test in browser

---

**Status:** ✅ **SELESAI & PRODUCTION READY**

TinyMCE sekarang 100% lokal dan siap digunakan tanpa dependency ke CDN eksternal!

**Author:** AI Assistant  
**Date:** October 13, 2025
