<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Article\Models\Article;
use App\Modules\Category\Models\Category;
use App\Modules\User\Models\User;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $categories = Category::all();
        
        $articles = [
            [
                'title_id' => 'Getting Started with Laravel 12',
                'title_en' => 'Getting Started with Laravel 12',
                'slug' => 'getting-started-with-laravel-12',
                'excerpt_id' => 'Learn the basics of Laravel 12 and discover what\'s new in this amazing PHP framework.',
                'excerpt_en' => 'Learn the basics of Laravel 12 and discover what\'s new in this amazing PHP framework.',
                'content_id' => '<h2>Introduction to Laravel 12</h2><p>Laravel 12 brings amazing new features and improvements. In this comprehensive guide, we\'ll explore everything you need to know to get started.</p><h3>Key Features</h3><ul><li>Improved performance</li><li>New Blade components</li><li>Enhanced security</li><li>Better developer experience</li></ul><p>Laravel continues to be one of the most popular PHP frameworks, and version 12 makes it even better. Whether you\'re building a small personal project or a large enterprise application, Laravel has the tools you need.</p>',
                'content_en' => '<h2>Introduction to Laravel 12</h2><p>Laravel 12 brings amazing new features and improvements. In this comprehensive guide, we\'ll explore everything you need to know to get started.</p><h3>Key Features</h3><ul><li>Improved performance</li><li>New Blade components</li><li>Enhanced security</li><li>Better developer experience</li></ul><p>Laravel continues to be one of the most popular PHP frameworks, and version 12 makes it even better. Whether you\'re building a small personal project or a large enterprise application, Laravel has the tools you need.</p>',
                'featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(1),
                'views' => 1250,
            ],
            [
                'title_id' => 'Mastering Tailwind CSS: A Complete Guide',
                'title_en' => 'Mastering Tailwind CSS: A Complete Guide',
                'slug' => 'mastering-tailwind-css-complete-guide',
                'excerpt_id' => 'Everything you need to know about Tailwind CSS, from basics to advanced techniques.',
                'excerpt_en' => 'Everything you need to know about Tailwind CSS, from basics to advanced techniques.',
                'content_id' => '<h2>Why Tailwind CSS?</h2><p>Tailwind CSS is a utility-first CSS framework that revolutionizes how we build user interfaces. Instead of writing custom CSS, you compose designs using pre-built utility classes.</p><h3>Benefits</h3><ul><li>Rapid development</li><li>Consistent design</li><li>Smaller CSS files</li><li>Easy customization</li></ul><p>Learn how to leverage Tailwind CSS to build beautiful, responsive websites faster than ever before.</p>',
                'content_en' => '<h2>Why Tailwind CSS?</h2><p>Tailwind CSS is a utility-first CSS framework that revolutionizes how we build user interfaces. Instead of writing custom CSS, you compose designs using pre-built utility classes.</p><h3>Benefits</h3><ul><li>Rapid development</li><li>Consistent design</li><li>Smaller CSS files</li><li>Easy customization</li></ul><p>Learn how to leverage Tailwind CSS to build beautiful, responsive websites faster than ever before.</p>',
                'featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(2),
                'views' => 890,
            ],
            [
                'title_id' => '10 Essential JavaScript ES2024 Features',
                'title_en' => '10 Essential JavaScript ES2024 Features',
                'slug' => '10-essential-javascript-es2024-features',
                'excerpt_id' => 'Discover the latest JavaScript features that will improve your code quality and productivity.',
                'excerpt_en' => 'Discover the latest JavaScript features that will improve your code quality and productivity.',
                'content_id' => '<h2>Modern JavaScript</h2><p>JavaScript continues to evolve with new features that make development easier and more efficient. ES2024 introduces several game-changing features.</p><h3>Top Features</h3><ol><li>Pipeline Operator</li><li>Record and Tuple</li><li>Temporal API</li><li>Decorators</li><li>Pattern Matching</li></ol><p>Stay ahead of the curve by learning these essential new JavaScript features.</p>',
                'content_en' => '<h2>Modern JavaScript</h2><p>JavaScript continues to evolve with new features that make development easier and more efficient. ES2024 introduces several game-changing features.</p><h3>Top Features</h3><ol><li>Pipeline Operator</li><li>Record and Tuple</li><li>Temporal API</li><li>Decorators</li><li>Pattern Matching</li></ol><p>Stay ahead of the curve by learning these essential new JavaScript features.</p>',
                'featured' => true,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'views' => 1520,
            ],
            [
                'title_id' => 'Building RESTful APIs with Laravel',
                'title_en' => 'Building RESTful APIs with Laravel',
                'slug' => 'building-restful-apis-with-laravel',
                'excerpt_id' => 'A step-by-step guide to creating powerful and secure REST APIs using Laravel.',
                'excerpt_en' => 'A step-by-step guide to creating powerful and secure REST APIs using Laravel.',
                'content_id' => '<h2>API Development with Laravel</h2><p>Learn how to build robust RESTful APIs using Laravel\'s powerful features. We\'ll cover authentication, rate limiting, versioning, and more.</p><p>Laravel makes API development a breeze with built-in support for JSON responses, API resources, and authentication via Sanctum or Passport.</p>',
                'content_en' => '<h2>API Development with Laravel</h2><p>Learn how to build robust RESTful APIs using Laravel\'s powerful features. We\'ll cover authentication, rate limiting, versioning, and more.</p><p>Laravel makes API development a breeze with built-in support for JSON responses, API resources, and authentication via Sanctum or Passport.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'views' => 680,
            ],
            [
                'title_id' => 'The Future of Web Development in 2025',
                'title_en' => 'The Future of Web Development in 2025',
                'slug' => 'future-of-web-development-2025',
                'excerpt_id' => 'Explore upcoming trends and technologies that will shape web development.',
                'excerpt_en' => 'Explore upcoming trends and technologies that will shape web development.',
                'content_id' => '<h2>Web Development Trends</h2><p>The web development landscape is constantly evolving. Let\'s look at what\'s coming in 2025.</p><h3>Key Trends</h3><ul><li>AI-powered development tools</li><li>WebAssembly adoption</li><li>Serverless architecture</li><li>JAMstack evolution</li></ul><p>Stay competitive by understanding and adopting these emerging technologies.</p>',
                'content_en' => '<h2>Web Development Trends</h2><p>The web development landscape is constantly evolving. Let\'s look at what\'s coming in 2025.</p><h3>Key Trends</h3><ul><li>AI-powered development tools</li><li>WebAssembly adoption</li><li>Serverless architecture</li><li>JAMstack evolution</li></ul><p>Stay competitive by understanding and adopting these emerging technologies.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'views' => 920,
            ],
            [
                'title_id' => 'UI/UX Design Best Practices 2025',
                'title_en' => 'UI/UX Design Best Practices 2025',
                'slug' => 'ui-ux-design-best-practices-2025',
                'excerpt_id' => 'Essential design principles and patterns for creating exceptional user experiences.',
                'excerpt_en' => 'Essential design principles and patterns for creating exceptional user experiences.',
                'content_id' => '<h2>Design Principles</h2><p>Great UI/UX design is crucial for product success. Learn the fundamental principles that guide effective design decisions.</p><h3>Core Principles</h3><ul><li>User-centered design</li><li>Accessibility first</li><li>Mobile-responsive</li><li>Performance optimization</li></ul><p>Master these principles to create interfaces that users love.</p>',
                'content_en' => '<h2>Design Principles</h2><p>Great UI/UX design is crucial for product success. Learn the fundamental principles that guide effective design decisions.</p><h3>Core Principles</h3><ul><li>User-centered design</li><li>Accessibility first</li><li>Mobile-responsive</li><li>Performance optimization</li></ul><p>Master these principles to create interfaces that users love.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(8),
                'views' => 540,
            ],
            [
                'title_id' => 'Database Optimization Techniques',
                'title_en' => 'Database Optimization Techniques',
                'slug' => 'database-optimization-techniques',
                'excerpt_id' => 'Boost your database performance with these proven optimization strategies.',
                'excerpt_en' => 'Boost your database performance with these proven optimization strategies.',
                'content_id' => '<h2>Database Performance</h2><p>Slow database queries can cripple your application. Learn how to identify bottlenecks and optimize your database for maximum performance.</p><h3>Optimization Strategies</h3><ul><li>Proper indexing</li><li>Query optimization</li><li>Connection pooling</li><li>Caching strategies</li></ul><p>Apply these techniques to dramatically improve your application\'s speed.</p>',
                'content_en' => '<h2>Database Performance</h2><p>Slow database queries can cripple your application. Learn how to identify bottlenecks and optimize your database for maximum performance.</p><h3>Optimization Strategies</h3><ul><li>Proper indexing</li><li>Query optimization</li><li>Connection pooling</li><li>Caching strategies</li></ul><p>Apply these techniques to dramatically improve your application\'s speed.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'views' => 720,
            ],
            [
                'title_id' => 'Microservices Architecture Explained',
                'title_en' => 'Microservices Architecture Explained',
                'slug' => 'microservices-architecture-explained',
                'excerpt_id' => 'Understanding microservices: benefits, challenges, and implementation patterns.',
                'excerpt_en' => 'Understanding microservices: benefits, challenges, and implementation patterns.',
                'content_id' => '<h2>Microservices Overview</h2><p>Microservices architecture has become the standard for building scalable applications. Learn when and how to implement this pattern.</p><h3>Key Concepts</h3><ul><li>Service decomposition</li><li>API gateway</li><li>Service discovery</li><li>Distributed tracing</li></ul><p>Understand the trade-offs and best practices for microservices architecture.</p>',
                'content_en' => '<h2>Microservices Overview</h2><p>Microservices architecture has become the standard for building scalable applications. Learn when and how to implement this pattern.</p><h3>Key Concepts</h3><ul><li>Service decomposition</li><li>API gateway</li><li>Service discovery</li><li>Distributed tracing</li></ul><p>Understand the trade-offs and best practices for microservices architecture.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(12),
                'views' => 810,
            ],
            [
                'title_id' => 'Securing Your Web Applications',
                'title_en' => 'Securing Your Web Applications',
                'slug' => 'securing-your-web-applications',
                'excerpt_id' => 'Essential security practices every developer must know to protect their applications.',
                'excerpt_en' => 'Essential security practices every developer must know to protect their applications.',
                'content_id' => '<h2>Web Security Fundamentals</h2><p>Security should never be an afterthought. Learn how to protect your applications from common vulnerabilities and attacks.</p><h3>Security Checklist</h3><ul><li>Input validation</li><li>Authentication & authorization</li><li>HTTPS/SSL</li><li>SQL injection prevention</li><li>XSS protection</li></ul><p>Implement these security measures to keep your users\' data safe.</p>',
                'content_en' => '<h2>Web Security Fundamentals</h2><p>Security should never be an afterthought. Learn how to protect your applications from common vulnerabilities and attacks.</p><h3>Security Checklist</h3><ul><li>Input validation</li><li>Authentication & authorization</li><li>HTTPS/SSL</li><li>SQL injection prevention</li><li>XSS protection</li></ul><p>Implement these security measures to keep your users\' data safe.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(14),
                'views' => 950,
            ],
            [
                'title_id' => 'Testing Strategies for Modern Applications',
                'title_en' => 'Testing Strategies for Modern Applications',
                'slug' => 'testing-strategies-modern-applications',
                'excerpt_id' => 'Comprehensive guide to testing methodologies and tools for building reliable software.',
                'excerpt_en' => 'Comprehensive guide to testing methodologies and tools for building reliable software.',
                'content_id' => '<h2>Software Testing</h2><p>Writing tests is essential for maintaining code quality and catching bugs early. Learn different testing strategies and when to use them.</p><h3>Testing Types</h3><ul><li>Unit testing</li><li>Integration testing</li><li>E2E testing</li><li>Performance testing</li></ul><p>Build confidence in your code with comprehensive testing strategies.</p>',
                'content_en' => '<h2>Software Testing</h2><p>Writing tests is essential for maintaining code quality and catching bugs early. Learn different testing strategies and when to use them.</p><h3>Testing Types</h3><ul><li>Unit testing</li><li>Integration testing</li><li>E2E testing</li><li>Performance testing</li></ul><p>Build confidence in your code with comprehensive testing strategies.</p>',
                'featured' => false,
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(16),
                'views' => 630,
            ],
        ];

        foreach ($articles as $index => $articleData) {
            // Assign articles to categories in a round-robin fashion
            $category = $categories[$index % $categories->count()];
            
            Article::create(array_merge($articleData, [
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]));
        }
    }
}
