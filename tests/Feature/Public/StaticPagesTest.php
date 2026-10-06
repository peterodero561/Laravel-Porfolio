<?php

namespace Tests\Feature\Public;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Support\SeedsPortfolio;
use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    use RefreshDatabase, SeedsPortfolio;

    public function test_about_page_loads(): void
    {
        $this->seedPortfolio();

        $this->get('/about')->assertOk()->assertSee('About');
    }

    public function test_services_page_lists_published_services(): void
    {
        $this->seedPortfolio();

        $this->get('/services')->assertOk()->assertSee('Services');
    }

    public function test_resume_page_loads_without_file(): void
    {
        $this->get('/resume')->assertOk()->assertSee('Resume');
    }

    public function test_resume_page_shows_download_when_file_exists(): void
    {
        SiteSetting::set('resume_file', 'resume/cv.pdf');
        Storage::disk('public')->put('resume/cv.pdf', 'PDF content');

        $this->get('/resume')
            ->assertOk()
            ->assertSee('Download PDF');
    }
}
