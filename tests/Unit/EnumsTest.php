<?php

namespace Tests\Unit;

use App\Enums\ProjectCategory;
use App\Enums\SkillCategory;
use PHPUnit\Framework\TestCase;

class EnumsTest extends TestCase
{
    public function test_project_category_labels_are_non_empty(): void
    {
        foreach (ProjectCategory::cases() as $case) {
            $this->assertNotEmpty($case->label());
        }
    }

    public function test_project_category_options_map_back_to_values(): void
    {
        $options = ProjectCategory::options();

        $this->assertArrayHasKey('web', $options);
        $this->assertArrayHasKey('mobile', $options);
        $this->assertArrayHasKey('ai', $options);
        $this->assertSame('Web', $options['web']);
    }

    public function test_skill_category_options_are_complete(): void
    {
        $options = SkillCategory::options();

        foreach (SkillCategory::cases() as $case) {
            $this->assertArrayHasKey($case->value, $options);
        }
    }
}
