<?php

namespace Tests\Support;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Technology;

trait SeedsPortfolio
{
    protected function seedPortfolio(): void
    {
        $technologies = Technology::factory()->count(8)->create();

        Project::factory()
            ->count(6)
            ->featured()
            ->create(['published' => true])
            ->each(fn ($p) => $p->technologies()->attach(
                $technologies->random(3)->pluck('id'),
            ));

        Project::factory()
            ->count(4)
            ->create(['published' => true, 'featured' => false]);

        Project::factory()
            ->count(2)
            ->create(['published' => false, 'featured' => false]);

        Skill::factory()->count(12)->create();
        Experience::factory()->count(3)->create();
        Service::factory()->count(5)->create();

        SiteSetting::set('name', 'Test Person');
        SiteSetting::set('professional_title', 'Software Developer');
        SiteSetting::set('short_bio', 'I build things.');
        SiteSetting::set('email', 'hello@example.test');
        SiteSetting::set('github_url', 'https://github.com/test');
    }
}
