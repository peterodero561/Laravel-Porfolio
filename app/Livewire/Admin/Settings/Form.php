<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use App\Services\PortfolioService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $professional_title = '';

    public string $short_bio = '';

    public string $email = '';

    public string $phone = '';

    public string $location = '';

    public string $github_url = '';

    public string $linkedin_url = '';

    public string $twitter_url = '';

    public string $website_url = '';

    public string $availability_status = 'available';

    public $profile_image = null;

    public ?string $existingProfileImage = null;

    public $resume_file = null;

    public ?string $existingResumeFile = null;

    public function mount(): void
    {
        foreach ([
            'name', 'professional_title', 'short_bio', 'email', 'phone', 'location',
            'github_url', 'linkedin_url', 'twitter_url', 'website_url', 'availability_status',
        ] as $key) {
            $this->{$key} = (string) SiteSetting::get($key, '');
        }

        $this->existingProfileImage = SiteSetting::get('profile_image');
        $this->existingResumeFile = SiteSetting::get('resume_file');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'professional_title' => ['required', 'string', 'max:100'],
            'short_bio' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'location' => ['nullable', 'string', 'max:100'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'availability_status' => ['required', 'in:available,limited,unavailable'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function removeProfileImage(): void
    {
        if ($this->existingProfileImage) {
            Storage::disk('public')->delete($this->existingProfileImage);
            SiteSetting::set('profile_image', null);
            $this->existingProfileImage = null;
        }
        $this->profile_image = null;
    }

    public function removeResume(): void
    {
        if ($this->existingResumeFile) {
            Storage::disk('public')->delete($this->existingResumeFile);
            SiteSetting::set('resume_file', null);
            $this->existingResumeFile = null;
        }
        $this->resume_file = null;
    }

    public function save(): void
    {
        $data = $this->validate();

        foreach ([
            'name', 'professional_title', 'short_bio', 'email', 'phone', 'location',
            'github_url', 'linkedin_url', 'twitter_url', 'website_url', 'availability_status',
        ] as $key) {
            SiteSetting::set($key, $data[$key] ?: null);
        }

        if ($this->profile_image) {
            if ($this->existingProfileImage) {
                Storage::disk('public')->delete($this->existingProfileImage);
            }
            $path = $this->profile_image->store('profile', 'public');
            SiteSetting::set('profile_image', $path);
            $this->existingProfileImage = $path;
            $this->profile_image = null;
        }

        if ($this->resume_file) {
            if ($this->existingResumeFile) {
                Storage::disk('public')->delete($this->existingResumeFile);
            }
            $path = $this->resume_file->store('resume', 'public');
            SiteSetting::set('resume_file', $path);
            $this->existingResumeFile = $path;
            $this->resume_file = null;
        }

        // Bust the settings cache explicitly — the model event fires too,
        // but this covers the multi-key batch write above.
        app(PortfolioService::class)->forget(PortfolioService::CACHE_SETTINGS);

        $this->dispatch('toast', type: 'success', message: 'Settings saved.');
    }

    public function render()
    {
        return view('livewire.admin.settings.form')
            ->layout('components.layouts.admin', ['title' => 'Site settings']);
    }
}
