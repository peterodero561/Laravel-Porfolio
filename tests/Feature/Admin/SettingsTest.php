<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Settings\Form;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin());

        foreach (['name', 'professional_title', 'short_bio', 'email', 'availability_status'] as $key) {
            SiteSetting::set($key, null);
        }
    }

    public function test_admin_can_update_settings(): void
    {
        Livewire::test(Form::class)
            ->set('name', 'New Name')
            ->set('professional_title', 'Lead Developer')
            ->set('short_bio', 'Building software.')
            ->set('email', 'new@example.test')
            ->set('availability_status', 'available')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('New Name', SiteSetting::get('name'));
        $this->assertSame('Lead Developer', SiteSetting::get('professional_title'));
    }

    public function test_invalid_email_is_rejected(): void
    {
        Livewire::test(Form::class)
            ->set('name', 'Name')
            ->set('professional_title', 'Dev')
            ->set('email', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['email']);
    }

    public function test_profile_image_upload_is_stored(): void
    {
        $file = UploadedFile::fake()->image('profile.jpg', 600, 600);

        Livewire::test(Form::class)
            ->set('name', 'Name')
            ->set('availability_status', 'available')
            ->set('professional_title', 'Dev')
            ->set('profile_image', $file)
            ->call('save')
            ->assertHasNoErrors();

        $path = SiteSetting::get('profile_image');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_resume_pdf_upload_is_stored(): void
    {
        $file = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf');

        Livewire::test(Form::class)
            ->set('name', 'Name')
            ->set('availability_status', 'available')
            ->set('professional_title', 'Dev')
            ->set('resume_file', $file)
            ->call('save')
            ->assertHasNoErrors();

        $path = SiteSetting::get('resume_file');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_resume_rejects_non_pdf(): void
    {
        $file = UploadedFile::fake()->create('cv.txt', 100, 'text/plain');

        Livewire::test(Form::class)
            ->set('name', 'Name')
            ->set('professional_title', 'Dev')
            ->set('resume_file', $file)
            ->call('save')
            ->assertHasErrors(['resume_file']);
    }

    public function test_removing_profile_image_deletes_the_file(): void
    {
        Storage::disk('public')->put('profile/old.jpg', 'fake');
        SiteSetting::set('profile_image', 'profile/old.jpg');

        Livewire::test(Form::class)
            ->call('removeProfileImage');

        Storage::disk('public')->assertMissing('profile/old.jpg');
        $this->assertNull(SiteSetting::get('profile_image'));
    }
}
