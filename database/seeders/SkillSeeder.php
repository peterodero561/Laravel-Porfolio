<?php

namespace Database\Seeders;

use App\Enums\SkillCategory;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            SkillCategory::Backend->value => [
                'PHP', 'Laravel', 'Livewire', 'REST APIs', 'PostgreSQL',
                'MySQL', 'Authentication', 'Queues', 'Caching',
            ],
            SkillCategory::Mobile->value => [
                'Flutter', 'Dart', 'Firebase', 'REST APIs', 'Push Notifications',
            ],
            SkillCategory::Ai->value => [
                'LLMs', 'Prompt Engineering', 'n8n', 'Webhooks', 'Vector Search', 'Automation',
            ],
            SkillCategory::Devops->value => [
                'Linux', 'Git', 'Docker', 'Nginx', 'CI/CD', 'Cloud',
            ],
        ];

        foreach ($groups as $category => $names) {
            foreach ($names as $i => $name) {
                Skill::updateOrCreate(
                    ['name' => $name, 'category' => $category],
                    ['published' => true, 'sort_order' => $i],
                );
            }
        }
    }
}
