<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;
use Carbon\Carbon;

class PageSeeder extends Seeder
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

        $pages = [
            [
                'title_id' => 'About Us',
                'title_en' => 'About Us',
                'slug' => 'about-us',
                'content_id' => '<h2>About Our Company</h2><p>We are a modern content management system built with Laravel 12, designed to help businesses and individuals create beautiful, multilingual websites with ease.</p><h3>Our Mission</h3><p>To provide a powerful yet simple platform that empowers creators to share their stories and ideas with the world, regardless of language barriers.</p><h3>Our Vision</h3><p>A world where content creation is accessible to everyone, everywhere, in any language.</p><h3>Our Values</h3><ul><li><strong>Innovation:</strong> We constantly push the boundaries of what\'s possible</li><li><strong>Accessibility:</strong> Making technology available to everyone</li><li><strong>Quality:</strong> Delivering excellence in every aspect</li><li><strong>Community:</strong> Building together with our users</li></ul>',
                'content_en' => '<h2>About Our Company</h2><p>We are a modern content management system built with Laravel 12, designed to help businesses and individuals create beautiful, multilingual websites with ease.</p><h3>Our Mission</h3><p>To provide a powerful yet simple platform that empowers creators to share their stories and ideas with the world, regardless of language barriers.</p><h3>Our Vision</h3><p>A world where content creation is accessible to everyone, everywhere, in any language.</p><h3>Our Values</h3><ul><li><strong>Innovation:</strong> We constantly push the boundaries of what\'s possible</li><li><strong>Accessibility:</strong> Making technology available to everyone</li><li><strong>Quality:</strong> Delivering excellence in every aspect</li><li><strong>Community:</strong> Building together with our users</li></ul>',
                'excerpt' => 'Learn more about our company, mission, vision, and values.',
                'template' => 'default',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(30),
                'meta_title' => 'About Us - Our Company Story',
                'meta_description' => 'Learn about our company mission, vision, and values. Discover how we\'re revolutionizing content management with multilingual support.',
                'meta_keywords' => 'about us, company, mission, vision, values',
                'order' => 1,
            ],
            [
                'title_id' => 'Contact Us',
                'title_en' => 'Contact Us',
                'slug' => 'contact-us',
                'content_id' => '<h2>Get In Touch</h2><p>We\'d love to hear from you! Whether you have questions, feedback, or just want to say hello, don\'t hesitate to reach out.</p><h3>Contact Information</h3><div class="contact-info"><p><strong>Email:</strong> contact@example.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Main Street, City, State 12345</p></div><h3>Business Hours</h3><p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p><p><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p><p><strong>Sunday:</strong> Closed</p><h3>Send us a Message</h3><p>Use the contact form below to send us your questions or feedback. We typically respond within 24 hours.</p>',
                'content_en' => '<h2>Get In Touch</h2><p>We\'d love to hear from you! Whether you have questions, feedback, or just want to say hello, don\'t hesitate to reach out.</p><h3>Contact Information</h3><div class="contact-info"><p><strong>Email:</strong> contact@example.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Main Street, City, State 12345</p></div><h3>Business Hours</h3><p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p><p><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p><p><strong>Sunday:</strong> Closed</p><h3>Send us a Message</h3><p>Use the contact form below to send us your questions or feedback. We typically respond within 24 hours.</p>',
                'excerpt' => 'Get in touch with us for questions, feedback, or support.',
                'template' => 'default',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(25),
                'meta_title' => 'Contact Us - Get In Touch',
                'meta_description' => 'Contact us for questions, feedback, or support. Find our contact information, business hours, and send us a message.',
                'meta_keywords' => 'contact, support, help, questions, feedback',
                'order' => 2,
            ],
            [
                'title_id' => 'Privacy Policy',
                'title_en' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content_id' => '<h2>Privacy Policy</h2><p>This privacy policy explains how we collect, use, and protect your personal information when you use our website and services.</p><h3>Information We Collect</h3><p>We may collect the following types of information:</p><ul><li><strong>Personal Information:</strong> Name, email address, phone number</li><li><strong>Usage Data:</strong> Pages visited, time spent on site, browser information</li><li><strong>Cookies:</strong> Small text files stored on your device</li></ul><h3>How We Use Your Information</h3><p>We use the collected information to:</p><ul><li>Provide and maintain our services</li><li>Communicate with you about our services</li><li>Improve our website and services</li><li>Send marketing communications (with your consent)</li></ul><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p><h3>Your Rights</h3><p>You have the right to:</p><ul><li>Access your personal data</li><li>Correct inaccurate data</li><li>Request deletion of your data</li><li>Object to data processing</li><li>Data portability</li></ul>',
                'content_en' => '<h2>Privacy Policy</h2><p>This privacy policy explains how we collect, use, and protect your personal information when you use our website and services.</p><h3>Information We Collect</h3><p>We may collect the following types of information:</p><ul><li><strong>Personal Information:</strong> Name, email address, phone number</li><li><strong>Usage Data:</strong> Pages visited, time spent on site, browser information</li><li><strong>Cookies:</strong> Small text files stored on your device</li></ul><h3>How We Use Your Information</h3><p>We use the collected information to:</p><ul><li>Provide and maintain our services</li><li>Communicate with you about our services</li><li>Improve our website and services</li><li>Send marketing communications (with your consent)</li></ul><h3>Data Security</h3><p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p><h3>Your Rights</h3><p>You have the right to:</p><ul><li>Access your personal data</li><li>Correct inaccurate data</li><li>Request deletion of your data</li><li>Object to data processing</li><li>Data portability</li></ul>',
                'excerpt' => 'Learn about our privacy policy and how we protect your data.',
                'template' => 'default',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(20),
                'meta_title' => 'Privacy Policy - Data Protection',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                'meta_keywords' => 'privacy, policy, data protection, GDPR',
                'order' => 3,
            ],
            [
                'title_id' => 'Terms of Service',
                'title_en' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content_id' => '<h2>Terms of Service</h2><p>By using our website and services, you agree to be bound by these terms of service. Please read them carefully.</p><h3>Acceptance of Terms</h3><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h3>Use License</h3><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p><h3>User Responsibilities</h3><p>You agree to:</p><ul><li>Use the service only for lawful purposes</li><li>Not use the service in any way that could damage or overburden it</li><li>Not attempt to gain unauthorized access to any part of the service</li><li>Respect the rights of other users</li></ul><h3>Content Policy</h3><p>You are responsible for the content you post on our platform. We reserve the right to remove content that violates our policies.</p><h3>Termination</h3><p>We may terminate or suspend your account immediately, without prior notice, for any reason whatsoever.</p><h3>Disclaimer</h3><p>The information on this website is provided on an \'as is\' basis. We disclaim all warranties, express or implied.</p>',
                'content_en' => '<h2>Terms of Service</h2><p>By using our website and services, you agree to be bound by these terms of service. Please read them carefully.</p><h3>Acceptance of Terms</h3><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h3>Use License</h3><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p><h3>User Responsibilities</h3><p>You agree to:</p><ul><li>Use the service only for lawful purposes</li><li>Not use the service in any way that could damage or overburden it</li><li>Not attempt to gain unauthorized access to any part of the service</li><li>Respect the rights of other users</li></ul><h3>Content Policy</h3><p>You are responsible for the content you post on our platform. We reserve the right to remove content that violates our policies.</p><h3>Termination</h3><p>We may terminate or suspend your account immediately, without prior notice, for any reason whatsoever.</p><h3>Disclaimer</h3><p>The information on this website is provided on an \'as is\' basis. We disclaim all warranties, express or implied.</p>',
                'excerpt' => 'Read our terms of service and usage policies.',
                'template' => 'default',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(15),
                'meta_title' => 'Terms of Service - Usage Policies',
                'meta_description' => 'Read our terms of service to understand the rules and policies for using our website and services.',
                'meta_keywords' => 'terms, service, policies, usage, agreement',
                'order' => 4,
            ],
            [
                'title_id' => 'Services',
                'title_en' => 'Services',
                'slug' => 'services',
                'content_id' => '<h2>Our Services</h2><p>We offer a comprehensive range of services to help you build and grow your online presence.</p><h3>Web Development</h3><p>Custom website development using modern technologies and best practices. We create responsive, fast, and secure websites.</p><h3>Content Management</h3><p>Professional content creation and management services. From blog posts to marketing copy, we help you tell your story.</p><h3>SEO Optimization</h3><p>Improve your search engine rankings with our comprehensive SEO services. Drive more traffic to your website.</p><h3>Digital Marketing</h3><p>Complete digital marketing solutions including social media management, email marketing, and paid advertising.</p><h3>Consulting</h3><p>Expert advice on technology strategy, digital transformation, and business growth.</p><h3>Support & Maintenance</h3><p>Ongoing support and maintenance to keep your website running smoothly and securely.</p>',
                'content_en' => '<h2>Our Services</h2><p>We offer a comprehensive range of services to help you build and grow your online presence.</p><h3>Web Development</h3><p>Custom website development using modern technologies and best practices. We create responsive, fast, and secure websites.</p><h3>Content Management</h3><p>Professional content creation and management services. From blog posts to marketing copy, we help you tell your story.</p><h3>SEO Optimization</h3><p>Improve your search engine rankings with our comprehensive SEO services. Drive more traffic to your website.</p><h3>Digital Marketing</h3><p>Complete digital marketing solutions including social media management, email marketing, and paid advertising.</p><h3>Consulting</h3><p>Expert advice on technology strategy, digital transformation, and business growth.</p><h3>Support & Maintenance</h3><p>Ongoing support and maintenance to keep your website running smoothly and securely.</p>',
                'excerpt' => 'Explore our comprehensive range of services and solutions.',
                'template' => 'default',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'meta_title' => 'Our Services - What We Offer',
                'meta_description' => 'Discover our comprehensive range of services including web development, content management, SEO, and digital marketing.',
                'meta_keywords' => 'services, web development, SEO, digital marketing, consulting',
                'order' => 5,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create(array_merge($pageData, [
                'user_id' => $user->id,
            ]));
        }
    }
}
