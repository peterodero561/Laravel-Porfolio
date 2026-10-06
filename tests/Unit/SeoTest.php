<?php

namespace Tests\Unit;

use App\Support\Seo;
use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_defaults_are_applied(): void
    {
        $seo = Seo::make()->resolve([
            'name' => 'Test Person',
            'professional_title' => 'Developer',
            'short_bio' => 'Bio text.',
        ]);

        $this->assertSame('Test Person — Developer', $seo['title']);
        $this->assertSame('Bio text.', $seo['description']);
        $this->assertSame('website', $seo['type']);
        $this->assertFalse($seo['noindex']);
    }

    public function test_explicit_values_override_defaults(): void
    {
        $seo = Seo::make()
            ->title('Projects')
            ->description('All of my projects')
            ->type('article')
            ->noindex()
            ->resolve(['name' => 'Site']);

        $this->assertSame('Projects — Site', $seo['title']);
        $this->assertSame('All of my projects', $seo['description']);
        $this->assertSame('article', $seo['type']);
        $this->assertTrue($seo['noindex']);
    }

    public function test_twitter_handle_is_extracted_from_url(): void
    {
        $seo = Seo::make()->resolve([
            'name' => 'Site',
            'twitter_url' => 'https://twitter.com/example_handle',
        ]);

        $this->assertSame('@example_handle', $seo['twitterHandle']);

        $seo = Seo::make()->resolve([
            'name' => 'Site',
            'twitter_url' => 'https://x.com/other_handle',
        ]);

        $this->assertSame('@other_handle', $seo['twitterHandle']);
    }
}
