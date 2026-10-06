<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Web Application Development', 'Full-stack Laravel applications built for scale, from MVP to production.', ['Laravel', 'Livewire', 'Tailwind CSS']],
            ['Mobile App Development', 'Cross-platform Flutter apps with native-quality UX.', ['Flutter', 'Dart', 'Firebase']],
            ['API Development', 'Well-documented REST APIs designed for mobile and third-party consumers.', ['Laravel', 'PostgreSQL', 'OpenAPI']],
            ['Laravel Development', 'Deep Laravel expertise: queues, caching, policies, testing, production hardening.', ['Laravel', 'Redis', 'MySQL']],
            ['Flutter Development', 'Flutter apps with offline-first sync, push notifications and clean architecture.', ['Flutter', 'Dart', 'REST APIs']],
            ['AI Integration', 'LLM-powered features: search, summarisation, extraction and ranking.', ['LLMs', 'OpenAI', 'Vector Search']],
            ['Workflow Automation', 'n8n and webhook pipelines that connect the tools you already use.', ['n8n', 'Webhooks', 'Laravel']],
            ['IoT Backend Development', 'Reliable backends for sensor fleets: ingestion, storage and query layers.', ['Laravel', 'PostgreSQL', 'MQTT']],
        ];

        foreach ($services as $i => [$title, $description, $technologies]) {
            Service::updateOrCreate(
                ['title' => $title],
                [
                    'description' => $description,
                    'technologies' => $technologies,
                    'published' => true,
                    'sort_order' => $i + 1,
                ],
            );
        }
    }
}
