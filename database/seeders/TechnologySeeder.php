<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'PHP', 'Laravel', 'Livewire', 'MySQL', 'PostgreSQL', 'Redis',
            'REST APIs', 'Flutter', 'Dart', 'Firebase', 'Node.js',
            'LLMs', 'OpenAI', 'n8n', 'Vector Search', 'Webhooks',
            'Docker', 'Linux', 'Nginx', 'Git', 'CI/CD',
            'Tailwind CSS', 'Alpine.js', 'JavaScript', 'TypeScript',
        ];

        foreach ($names as $name) {
            Technology::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
