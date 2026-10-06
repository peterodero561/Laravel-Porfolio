<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'company' => 'Freelance',
                'position' => 'Software Developer',
                'location' => 'Remote',
                'start_date' => '2022-01-01',
                'end_date' => null,
                'is_current' => true,
                'description' => 'Designing and shipping full-stack applications for clients across property, logistics and fintech.',
                'responsibilities' => [
                    'End-to-end delivery of Laravel + Flutter products',
                    'API design and third-party integrations',
                    'Database modelling and performance tuning',
                ],
                'technologies' => ['Laravel', 'Flutter', 'PostgreSQL', 'Docker'],
                'sort_order' => 1,
            ],
            [
                'company' => 'Example Corp',
                'position' => 'Backend Developer',
                'location' => 'Nairobi, Kenya',
                'start_date' => '2020-06-01',
                'end_date' => '2021-12-31',
                'is_current' => false,
                'description' => 'Built and maintained internal APIs powering a multi-tenant SaaS platform.',
                'responsibilities' => [
                    'Designed REST APIs consumed by mobile and web clients',
                    'Reduced p95 API latency by 45% through query and cache work',
                    'Mentored junior developers on Laravel conventions',
                ],
                'technologies' => ['Laravel', 'MySQL', 'Redis', 'Docker'],
                'sort_order' => 2,
            ],
        ];

        foreach ($entries as $entry) {
            Experience::updateOrCreate(
                ['company' => $entry['company'], 'position' => $entry['position']],
                $entry,
            );
        }
    }
}
