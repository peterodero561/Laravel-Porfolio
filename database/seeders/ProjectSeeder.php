<?php

namespace Database\Seeders;

use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'ShifTenant',
                'slug' => 'shifttenant',
                'short_description' => 'Property management platform for landlords and tenants, with AI-assisted onboarding.',
                'category' => ProjectCategory::Mobile,
                'featured' => true,
                'sort_order' => 1,
                'project_date' => '2024-11-01',
                'technologies' => ['Flutter', 'Laravel', 'PostgreSQL', 'Firebase', 'LLMs', 'n8n'],
                'problem' => 'Small landlords and tenants lacked a shared, low-friction system to manage leases, payments and repairs.',
                'solution' => 'A Flutter app on top of a Laravel API, with AI-assisted document extraction and n8n automations for reminders.',
                'features' => [
                    'Multi-tenant lease and payment tracking',
                    'AI-powered document extraction for contracts',
                    'Automated rent reminders via n8n',
                    'Push notifications for tenant requests',
                ],
                'architecture' => "Flutter\n  ↓\nLaravel API\n  ↓\nPostgreSQL + Firebase",
                'technical_implementation' => 'REST API built with Laravel, JWT auth, queued jobs for notifications, PostgreSQL with composite indexes for tenant scoping.',
                'challenges' => 'Handling offline-first data sync in Flutter without duplicating server logic.',
                'results' => 'Onboarded 200+ tenants in the first quarter; support tickets reduced by 40%.',
            ],
            [
                'title' => 'AI House Hunting',
                'slug' => 'ai-house-hunting',
                'short_description' => 'LLM-powered property search and ranking across multiple listing sources.',
                'category' => ProjectCategory::AI,
                'featured' => true,
                'sort_order' => 2,
                'project_date' => '2024-08-15',
                'technologies' => ['Flutter', 'n8n', 'LLMs', 'Laravel'],
                'problem' => 'Buyers had to manually cross-reference dozens of listings, each with different formats and quality.',
                'solution' => 'A pipeline that ingests listings, normalizes them, and ranks results against a natural-language brief.',
                'features' => [
                    'Natural-language property search',
                    'Multi-source listing ingestion',
                    'LLM-based relevance ranking',
                    'Saved briefs and alerts',
                ],
                'architecture' => "Flutter\n  ↓\nn8n\n  ↓\nLLM\n  ↓\nProperty Search → Ranking → Response",
                'technical_implementation' => 'n8n orchestration with webhook triggers, embeddings stored for vector search, LLM used for scoring and summarisation.',
                'challenges' => 'Keeping latency low enough for a responsive search UX while using an LLM in the loop.',
                'results' => 'Cut average time-to-shortlist from hours to under 5 minutes.',
            ],
            [
                'title' => 'Automation Dashboard',
                'slug' => 'automation-dashboard',
                'short_description' => 'Central dashboard for monitoring and replaying n8n workflows and webhook events.',
                'category' => ProjectCategory::Automation,
                'featured' => false,
                'sort_order' => 3,
                'project_date' => '2024-04-01',
                'technologies' => ['Laravel', 'Livewire', 'MySQL', 'Webhooks', 'Redis'],
                'problem' => 'Webhook failures were silent and workflow debugging required SSH access to the automation host.',
                'solution' => 'A Laravel + Livewire dashboard that stores incoming webhooks, visualizes success/failure, and replays failed events.',
                'features' => [
                    'Live webhook event stream',
                    'One-click replay of failed events',
                    'Per-workflow success metrics',
                    'Alerting via email and Slack',
                ],
                'architecture' => "Webhook sources\n  ↓\nLaravel (queue)\n  ↓\nMySQL + Redis\n  ↓\nLivewire dashboard",
                'technical_implementation' => 'Events stored in MySQL, processed via queued jobs, aggregated stats cached for the dashboard.',
                'challenges' => 'Ensuring idempotent replays for events that had already partially executed.',
                'results' => 'Reduced mean time to detect workflow issues from hours to seconds.',
            ],
            [
                'title' => 'IoT Sensor Backend',
                'slug' => 'iot-sensor-backend',
                'short_description' => 'Backend for ingesting, storing and querying high-frequency sensor telemetry.',
                'category' => ProjectCategory::IoT,
                'featured' => false,
                'sort_order' => 4,
                'project_date' => '2023-12-01',
                'technologies' => ['Laravel', 'PostgreSQL', 'Redis', 'Docker', 'Linux'],
                'problem' => 'Sensor devices produced far more data than the existing backend could ingest without backpressure.',
                'solution' => 'A queue-first ingestion pipeline that batches writes and serves reads from a cached time-series view.',
                'features' => [
                    'Batched ingestion',
                    'Backpressure handling',
                    'Downsampled historical queries',
                    'Device health monitoring',
                ],
                'architecture' => "Devices → MQTT → Laravel queue → PostgreSQL\n                                ↓\n                          Redis cache",
                'technical_implementation' => 'Queued jobs write in batches, Redis caches common read patterns, PostgreSQL partitioned by time.',
                'challenges' => 'Balancing ingestion throughput with query latency on the same database.',
                'results' => 'Sustained 50k events/minute without dropping samples.',
            ],
        ];

        foreach ($projects as $data) {
            $technologies = $data['technologies'];
            unset($data['technologies']);

            $project = Project::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['published' => true]),
            );

            $ids = Technology::whereIn('name', $technologies)->pluck('id');
            $project->technologies()->sync($ids);
        }
    }
}
