<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => $this->generateContent('About Us'),
                'meta_title' => 'About Our Company',
                'meta_description' => 'Learn more about our company and our mission.',
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-conditions',
                'content' => $this->generateContent('Terms and Conditions'),
                'meta_title' => 'Terms and Conditions',
                'meta_description' => 'Read our terms and conditions for using our platform.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => $this->generateContent('Privacy Policy'),
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Learn how we protect your privacy and data.',
            ],
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'content' => $this->generateContent('Shipping Policy'),
                'meta_title' => 'Shipping Policy',
                'meta_description' => 'Information about our shipping methods and delivery times.',
            ],
            [
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'content' => $this->generateContent('Return Policy'),
                'meta_title' => 'Return Policy',
                'meta_description' => 'Learn about our return and refund policies.',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => $this->generateContent('Contact Us'),
                'meta_title' => 'Contact Us',
                'meta_description' => 'Get in touch with our customer support team.',
            ],
        ];

        foreach ($pages as $page) {
            Page::create([
                'id' => Str::uuid(),
                'title' => $page['title'],
                'slug' => $page['slug'],
                'content' => $page['content'],
                'meta_title' => $page['meta_title'],
                'meta_description' => $page['meta_description'],
                'status' => true,
            ]);
        }

        echo "Pages seeded: " . Page::count() . "\n";
    }

    private function generateContent($title): string
    {
        return "<h1>{$title}</h1>
                <p>This is the content for the {$title} page. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <h2>Section 1</h2>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <h2>Section 2</h2>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>";
    }
}