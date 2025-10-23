<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Category\Models\Category;
use App\Modules\Article\Models\Article;
use App\Modules\Page\Models\Page;
use App\Modules\Media\Models\Media;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories
        $categories = [
            [
                'name_id' => 'Teknologi',
                'name_en' => 'Technology',
                'description_id' => 'Artikel tentang teknologi terbaru dan inovasi',
                'description_en' => 'Articles about latest technology and innovation',
                'slug' => 'teknologi',
                'is_active' => true,
                'locale' => 'id'
            ],
            [
                'name_id' => 'Pemrograman',
                'name_en' => 'Programming',
                'description_id' => 'Tutorial dan tips pemrograman',
                'description_en' => 'Programming tutorials and tips',
                'slug' => 'pemrograman',
                'is_active' => true,
                'locale' => 'id'
            ],
            [
                'name_id' => 'Web Development',
                'name_en' => 'Web Development',
                'description_id' => 'Panduan pengembangan web modern',
                'description_en' => 'Modern web development guides',
                'slug' => 'web-development',
                'is_active' => true,
                'locale' => 'id'
            ],
            [
                'name_id' => 'Laravel',
                'name_en' => 'Laravel',
                'description_id' => 'Framework PHP Laravel dan ekosistemnya',
                'description_en' => 'Laravel PHP framework and its ecosystem',
                'slug' => 'laravel',
                'is_active' => true,
                'locale' => 'id'
            ],
            [
                'name_id' => 'JavaScript',
                'name_en' => 'JavaScript',
                'description_id' => 'Bahasa pemrograman JavaScript dan framework',
                'description_en' => 'JavaScript programming language and frameworks',
                'slug' => 'javascript',
                'is_active' => true,
                'locale' => 'id'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Get categories for articles
        $techCategory = Category::where('slug', 'teknologi')->first();
        $programmingCategory = Category::where('slug', 'pemrograman')->first();
        $webDevCategory = Category::where('slug', 'web-development')->first();
        $laravelCategory = Category::where('slug', 'laravel')->first();
        $jsCategory = Category::where('slug', 'javascript')->first();

        // Create sample articles
        $articles = [
            [
                'title_id' => 'Panduan Lengkap Laravel 11: Fitur Terbaru dan Cara Menggunakannya',
                'title_en' => 'Complete Guide to Laravel 11: Latest Features and How to Use Them',
                'excerpt_id' => 'Laravel 11 hadir dengan banyak fitur baru yang menarik. Mari kita eksplorasi fitur-fitur terbaru dan cara menggunakannya dalam proyek Anda.',
                'excerpt_en' => 'Laravel 11 comes with many exciting new features. Let\'s explore the latest features and how to use them in your project.',
                'content_id' => '<h2>Pengenalan Laravel 11</h2><p>Laravel 11 adalah versi terbaru dari framework PHP yang populer ini. Dengan performa yang lebih baik dan fitur-fitur baru yang menarik, Laravel 11 membawa pengalaman pengembangan yang lebih baik.</p><h3>Fitur Terbaru</h3><ul><li>Performance improvements</li><li>New authentication system</li><li>Enhanced routing</li><li>Better database support</li></ul><p>Dengan semua fitur ini, Laravel 11 menjadi pilihan yang sempurna untuk proyek web modern Anda.</p>',
                'content_en' => '<h2>Introduction to Laravel 11</h2><p>Laravel 11 is the latest version of this popular PHP framework. With better performance and exciting new features, Laravel 11 brings a better development experience.</p><h3>Latest Features</h3><ul><li>Performance improvements</li><li>New authentication system</li><li>Enhanced routing</li><li>Better database support</li></ul><p>With all these features, Laravel 11 becomes the perfect choice for your modern web projects.</p>',
                'slug' => 'panduan-lengkap-laravel-11',
                'status' => 'published',
                'featured' => true,
                'views' => 1250,
                'user_id' => 1,
                'category_id' => $laravelCategory->id,
                'locale' => 'id'
            ],
            [
                'title_id' => 'JavaScript ES2024: Fitur Terbaru yang Harus Anda Ketahui',
                'title_en' => 'JavaScript ES2024: Latest Features You Must Know',
                'excerpt_id' => 'JavaScript terus berkembang dengan fitur-fitur baru setiap tahun. Mari kita lihat fitur-fitur terbaru di ES2024 yang akan membuat kode Anda lebih efisien.',
                'excerpt_en' => 'JavaScript continues to evolve with new features every year. Let\'s look at the latest features in ES2024 that will make your code more efficient.',
                'content_id' => '<h2>Fitur Baru ES2024</h2><p>ES2024 membawa banyak peningkatan yang signifikan untuk pengalaman pengembangan JavaScript.</p><h3>Array Methods Baru</h3><p>Beberapa method array baru yang sangat berguna untuk manipulasi data.</p><h3>Async Improvements</h3><p>Peningkatan pada async/await dan Promise handling.</p>',
                'content_en' => '<h2>New ES2024 Features</h2><p>ES2024 brings many significant improvements to the JavaScript development experience.</p><h3>New Array Methods</h3><p>Some new array methods that are very useful for data manipulation.</p><h3>Async Improvements</h3><p>Improvements to async/await and Promise handling.</p>',
                'slug' => 'javascript-es2024-fitur-terbaru',
                'status' => 'published',
                'featured' => true,
                'views' => 980,
                'user_id' => 1,
                'category_id' => $jsCategory->id,
                'locale' => 'id'
            ],
            [
                'title_id' => 'Membangun REST API dengan Laravel: Panduan Lengkap',
                'title_en' => 'Building REST API with Laravel: Complete Guide',
                'excerpt_id' => 'REST API adalah fondasi dari aplikasi web modern. Pelajari cara membangun REST API yang robust dengan Laravel.',
                'excerpt_en' => 'REST API is the foundation of modern web applications. Learn how to build robust REST APIs with Laravel.',
                'content_id' => '<h2>Dasar-dasar REST API</h2><p>REST API memungkinkan komunikasi antara aplikasi yang berbeda dengan menggunakan HTTP methods.</p><h3>HTTP Methods</h3><ul><li>GET - untuk mengambil data</li><li>POST - untuk membuat data baru</li><li>PUT - untuk mengupdate data</li><li>DELETE - untuk menghapus data</li></ul>',
                'content_en' => '<h2>REST API Basics</h2><p>REST API allows communication between different applications using HTTP methods.</p><h3>HTTP Methods</h3><ul><li>GET - to retrieve data</li><li>POST - to create new data</li><li>PUT - to update data</li><li>DELETE - to delete data</li></ul>',
                'slug' => 'membangun-rest-api-laravel',
                'status' => 'published',
                'featured' => false,
                'views' => 750,
                'user_id' => 1,
                'category_id' => $webDevCategory->id,
                'locale' => 'id'
            ],
            [
                'title_id' => 'Vue.js 3 Composition API: Panduan untuk Pemula',
                'title_en' => 'Vue.js 3 Composition API: Beginner\'s Guide',
                'excerpt_id' => 'Vue.js 3 memperkenalkan Composition API yang memberikan fleksibilitas lebih dalam mengorganisir kode komponen.',
                'excerpt_en' => 'Vue.js 3 introduces the Composition API which provides more flexibility in organizing component code.',
                'content_id' => '<h2>Apa itu Composition API?</h2><p>Composition API adalah cara baru untuk menulis komponen Vue.js yang lebih fleksibel dan mudah di-test.</p><h3>Keuntungan Composition API</h3><ul><li>Better code organization</li><li>Easier testing</li><li>Better TypeScript support</li><li>Reusable logic</li></ul>',
                'content_en' => '<h2>What is Composition API?</h2><p>Composition API is a new way to write Vue.js components that is more flexible and easier to test.</p><h3>Composition API Benefits</h3><ul><li>Better code organization</li><li>Easier testing</li><li>Better TypeScript support</li><li>Reusable logic</li></ul>',
                'slug' => 'vue-js-3-composition-api',
                'status' => 'published',
                'featured' => false,
                'views' => 650,
                'user_id' => 1,
                'category_id' => $jsCategory->id,
                'locale' => 'id'
            ],
            [
                'title_id' => 'Docker untuk Developer: Containerisasi Aplikasi Web',
                'title_en' => 'Docker for Developers: Web Application Containerization',
                'excerpt_id' => 'Docker memudahkan deployment dan pengembangan aplikasi web. Pelajari cara menggunakan Docker untuk proyek Anda.',
                'excerpt_en' => 'Docker makes web application deployment and development easier. Learn how to use Docker for your projects.',
                'content_id' => '<h2>Pengenalan Docker</h2><p>Docker adalah platform untuk mengembangkan, mengirim, dan menjalankan aplikasi menggunakan container.</p><h3>Keuntungan Docker</h3><ul><li>Consistency across environments</li><li>Easy deployment</li><li>Resource efficiency</li><li>Scalability</li></ul>',
                'content_en' => '<h2>Docker Introduction</h2><p>Docker is a platform for developing, shipping, and running applications using containers.</p><h3>Docker Benefits</h3><ul><li>Consistency across environments</li><li>Easy deployment</li><li>Resource efficiency</li><li>Scalability</li></ul>',
                'slug' => 'docker-untuk-developer',
                'status' => 'published',
                'featured' => false,
                'views' => 420,
                'user_id' => 1,
                'category_id' => $techCategory->id,
                'locale' => 'id'
            ]
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }

        // Create sample pages
        $pages = [
            [
                'title_id' => 'Tentang Kami',
                'title_en' => 'About Us',
                'content_id' => '<h2>Selamat Datang di CMS Laravel</h2><p>Kami adalah tim pengembang yang berdedikasi untuk menciptakan solusi web yang inovatif dan berkualitas tinggi.</p><h3>Misi Kami</h3><p>Memberikan platform CMS yang mudah digunakan, fleksibel, dan powerful untuk kebutuhan website modern.</p><h3>Visi Kami</h3><p>Menjadi platform CMS terdepan yang mendukung pengembangan web yang efisien dan efektif.</p>',
                'content_en' => '<h2>Welcome to Laravel CMS</h2><p>We are a team of dedicated developers creating innovative and high-quality web solutions.</p><h3>Our Mission</h3><p>To provide an easy-to-use, flexible, and powerful CMS platform for modern website needs.</p><h3>Our Vision</h3><p>To become the leading CMS platform that supports efficient and effective web development.</p>',
                'slug' => 'tentang-kami',
                'status' => 'published',
                'user_id' => 1,
                'locale' => 'id'
            ],
            [
                'title_id' => 'Kontak',
                'title_en' => 'Contact',
                'content_id' => '<h2>Hubungi Kami</h2><p>Kami siap membantu Anda dengan pertanyaan atau kebutuhan proyek Anda.</p><h3>Informasi Kontak</h3><ul><li>Email: info@laravelcms.com</li><li>Telepon: +62 123 456 7890</li><li>Alamat: Jakarta, Indonesia</li></ul><h3>Jam Operasional</h3><p>Senin - Jumat: 09:00 - 17:00 WIB</p>',
                'content_en' => '<h2>Contact Us</h2><p>We are ready to help you with questions or your project needs.</p><h3>Contact Information</h3><ul><li>Email: info@laravelcms.com</li><li>Phone: +62 123 456 7890</li><li>Address: Jakarta, Indonesia</li></ul><h3>Operating Hours</h3><p>Monday - Friday: 09:00 - 17:00 WIB</p>',
                'slug' => 'kontak',
                'status' => 'published',
                'user_id' => 1,
                'locale' => 'id'
            ],
            [
                'title_id' => 'Kebijakan Privasi',
                'title_en' => 'Privacy Policy',
                'content_id' => '<h2>Kebijakan Privasi</h2><p>Kami menghormati privasi pengguna dan berkomitmen untuk melindungi informasi pribadi Anda.</p><h3>Informasi yang Kami Kumpulkan</h3><p>Kami mengumpulkan informasi yang Anda berikan secara sukarela, seperti nama dan alamat email.</p><h3>Penggunaan Informasi</h3><p>Informasi yang kami kumpulkan digunakan untuk memberikan layanan yang lebih baik kepada Anda.</p>',
                'content_en' => '<h2>Privacy Policy</h2><p>We respect user privacy and are committed to protecting your personal information.</p><h3>Information We Collect</h3><p>We collect information you voluntarily provide, such as name and email address.</p><h3>Use of Information</h3><p>The information we collect is used to provide better services to you.</p>',
                'slug' => 'kebijakan-privasi',
                'status' => 'published',
                'user_id' => 1,
                'locale' => 'id'
            ]
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }

        // Create additional users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'author',
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Categories: ' . Category::count());
        $this->command->info('Articles: ' . Article::count());
        $this->command->info('Pages: ' . Page::count());
        $this->command->info('Users: ' . User::count());
    }
}