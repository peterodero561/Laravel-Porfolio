<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'name' => 'Odero Peter',
            'professional_title' => 'Software Developer',
            'short_bio' => 'I build modern digital products that solve real-world problems.',
            'email' => 'peterodero561@gmail.com',
            'phone' => null,
            'location' => 'Nairobi, Kenya',
            'github_url' => 'https://github.com/peterodero561',
            'linkedin_url' => 'https://linkedin.com/in/0deroPeter',
            'twitter_url' => null,
            'website_url' => null,
            'profile_image' => null,
            'resume_file' => null,
            'availability_status' => 'available',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
