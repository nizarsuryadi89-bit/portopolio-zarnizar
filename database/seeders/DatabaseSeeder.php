<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Keep the existing admin user creation, checking if exists first
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin Portfolio',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // password is password
            ]);
        }

        // Settings
        $settings = [
            ['key' => 'site_title', 'value' => 'Zarnizar Portfolio'],
            ['key' => 'site_description', 'value' => 'Professional Web Developer & Designer Portfolio.'],
            ['key' => 'hero_title', 'value' => 'Hi, I am Zarnizar'],
            ['key' => 'hero_subtitle', 'value' => 'Full-Stack Developer'],
        ];
        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        // Profile
        if (! Profile::first()) {
            Profile::create([
                'name' => 'Zarnizar',
                'title' => 'Full-Stack Developer',
                'tagline' => 'Building elegant solutions to complex problems.',
                'description' => "I am a passionate software engineer with 5 years of experience in building modern web applications. I love working with Laravel, Vue.js, and Tailwind CSS to create beautiful and performant user interfaces.\n\nMy goal is to continuously learn and improve my skills while delivering high-quality products.",
                'email' => 'contact@zarnizar.com',
                'whatsapp' => '6281234567890',
                'location' => 'Jakarta, Indonesia',
            ]);
        }

        // Skills
        $skills = [
            ['name' => 'Laravel', 'percentage' => 90, 'category' => 'Backend', 'icon' => 'bi bi-code-slash'],
            ['name' => 'PHP', 'percentage' => 85, 'category' => 'Backend', 'icon' => 'bi bi-filetype-php'],
            ['name' => 'Tailwind CSS', 'percentage' => 95, 'category' => 'Frontend', 'icon' => 'bi bi-palette'],
            ['name' => 'JavaScript', 'percentage' => 80, 'category' => 'Frontend', 'icon' => 'bi bi-filetype-js'],
        ];
        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }

        // Experiences
        $experiences = [
            [
                'position' => 'Senior Web Developer',
                'organization' => 'Tech Solutions Inc.',
                'start_date' => '2021-01-01',
                'is_current' => true,
                'description' => 'Leading the frontend team in developing high-performance web applications.',
            ],
            [
                'position' => 'Backend Developer',
                'organization' => 'Creative Agency',
                'start_date' => '2018-05-01',
                'end_date' => '2020-12-31',
                'is_current' => false,
                'description' => 'Developed RESTful APIs and maintained database architecture.',
            ],
        ];
        foreach ($experiences as $exp) {
            Experience::firstOrCreate(['position' => $exp['position']], $exp);
        }

        // Education
        $educations = [
            [
                'institution' => 'University of Technology',
                'degree' => 'Bachelor',
                'field' => 'Computer Science',
                'start_year' => '2014',
                'end_year' => '2018',
            ],
        ];
        foreach ($educations as $edu) {
            Education::firstOrCreate(['institution' => $edu['institution']], $edu);
        }

        // Social Links
        $socials = [
            ['name' => 'GitHub', 'url' => 'https://github.com', 'icon' => 'bi bi-github'],
            ['name' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'bi bi-linkedin'],
        ];
        foreach ($socials as $soc) {
            SocialLink::firstOrCreate(['name' => $soc['name']], $soc);
        }

        // Portfolios (Projects)
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'slug' => 'e-commerce-platform',
                'description' => 'A full-featured e-commerce platform built with Laravel and Vue.js.',
                'url' => 'https://example.com/ecommerce',
                'date' => '2023-10-15',
            ],
            [
                'title' => 'Company Profile Website',
                'slug' => 'company-profile-website',
                'description' => 'A modern and responsive corporate website using Tailwind CSS.',
                'url' => 'https://example.com/company',
                'date' => '2023-05-20',
            ],
        ];
        foreach ($projects as $proj) {
            Portfolio::firstOrCreate(['slug' => $proj['slug']], $proj);
        }

        // Activities
        $activities = [
            [
                'title' => 'Laravel Developer Conference 2023',
                'description' => 'Attended the annual Laravel developer conference to learn about the latest features and best practices.',
                'date' => '2023-11-10',
            ],
            [
                'title' => 'Advanced Frontend Workshop',
                'description' => 'Completed a 3-day intensive workshop on modern frontend architecture and performance optimization.',
                'date' => '2023-08-25',
            ],
        ];
        foreach ($activities as $act) {
            Activity::firstOrCreate(['title' => $act['title']], $act);
        }

        // Categories & Tags
        $cat1 = Category::firstOrCreate(['slug' => 'tutorial'], ['name' => 'Tutorial']);
        $cat2 = Category::firstOrCreate(['slug' => 'tips-tricks'], ['name' => 'Tips & Tricks']);

        $tag1 = Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel']);
        $tag2 = Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP']);
        $tag3 = Tag::firstOrCreate(['slug' => 'frontend'], ['name' => 'Frontend']);

        // Posts
        $post1 = Post::firstOrCreate(
            ['slug' => 'getting-started-with-laravel-11'],
            [
                'title' => 'Getting Started with Laravel 11',
                'content' => 'Laravel 11 introduces many new features and improvements. In this article, we will explore the new directory structure, the simplified configuration, and how to build a basic application...',
                'category_id' => $cat1->id,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ]
        );
        $post1->tags()->syncWithoutDetaching([$tag1->id, $tag2->id]);

        $post2 = Post::firstOrCreate(
            ['slug' => 'mastering-tailwind-css'],
            [
                'title' => 'Mastering Tailwind CSS for Modern UIs',
                'content' => 'Tailwind CSS is a utility-first CSS framework that allows you to build custom designs rapidly. Let us dive into advanced techniques, responsive design patterns, and how to create a design system...',
                'category_id' => $cat2->id,
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ]
        );
        $post2->tags()->syncWithoutDetaching([$tag3->id]);
    }
}
