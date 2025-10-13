<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Site Settings
            ['key' => 'site_name', 'value' => 'Laravel CMS', 'group' => 'site', 'type' => 'text', 'description' => 'Website name', 'is_public' => true],
            ['key' => 'site_description', 'value' => 'A modern content management system built with Laravel', 'group' => 'site', 'type' => 'textarea', 'description' => 'Site description for SEO', 'is_public' => true],
            ['key' => 'site_keywords', 'value' => 'laravel, cms, content management', 'group' => 'site', 'type' => 'text', 'description' => 'Site keywords for SEO', 'is_public' => true],
            ['key' => 'site_logo', 'value' => '/images/logo.png', 'group' => 'site', 'type' => 'text', 'description' => 'Site logo path', 'is_public' => true],
            ['key' => 'site_favicon', 'value' => '/images/favicon.ico', 'group' => 'site', 'type' => 'text', 'description' => 'Site favicon path', 'is_public' => true],
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'site', 'type' => 'boolean', 'description' => 'Enable/disable maintenance mode', 'is_public' => false],

            // Contact Settings
            ['key' => 'contact_email', 'value' => 'info@example.com', 'group' => 'contact', 'type' => 'text', 'description' => 'Main contact email', 'is_public' => true],
            ['key' => 'contact_phone', 'value' => '+1234567890', 'group' => 'contact', 'type' => 'text', 'description' => 'Contact phone number', 'is_public' => true],
            ['key' => 'contact_address', 'value' => '123 Main St, City, Country', 'group' => 'contact', 'type' => 'textarea', 'description' => 'Physical address', 'is_public' => true],

            // Social Media Links
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/yourpage', 'group' => 'social', 'type' => 'text', 'description' => 'Facebook page URL', 'is_public' => true],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/yourhandle', 'group' => 'social', 'type' => 'text', 'description' => 'Twitter/X profile URL', 'is_public' => true],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/yourhandle', 'group' => 'social', 'type' => 'text', 'description' => 'Instagram profile URL', 'is_public' => true],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@yourchannel', 'group' => 'social', 'type' => 'text', 'description' => 'YouTube channel URL', 'is_public' => true],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/yourcompany', 'group' => 'social', 'type' => 'text', 'description' => 'LinkedIn profile URL', 'is_public' => true],

            // Email Settings
            ['key' => 'email_from_name', 'value' => 'Laravel CMS', 'group' => 'email', 'type' => 'text', 'description' => 'Email sender name', 'is_public' => false],
            ['key' => 'email_from_address', 'value' => 'noreply@example.com', 'group' => 'email', 'type' => 'text', 'description' => 'Email sender address', 'is_public' => false],

            // Content Settings
            ['key' => 'posts_per_page', 'value' => '12', 'group' => 'content', 'type' => 'number', 'description' => 'Number of posts per page', 'is_public' => true],
            ['key' => 'excerpt_length', 'value' => '200', 'group' => 'content', 'type' => 'number', 'description' => 'Default excerpt length in characters', 'is_public' => false],
            ['key' => 'enable_comments', 'value' => 'true', 'group' => 'content', 'type' => 'boolean', 'description' => 'Enable/disable comments', 'is_public' => false],

            // SEO Settings
            ['key' => 'seo_title_separator', 'value' => '|', 'group' => 'seo', 'type' => 'text', 'description' => 'Title separator character', 'is_public' => false],
            ['key' => 'seo_meta_robots', 'value' => 'index, follow', 'group' => 'seo', 'type' => 'text', 'description' => 'Default robots meta tag', 'is_public' => false],
            ['key' => 'seo_og_image', 'value' => '/images/og-default.jpg', 'group' => 'seo', 'type' => 'text', 'description' => 'Default Open Graph image', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            \App\Modules\Setting\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
