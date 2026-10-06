<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class Seo
{
    private ?string $title = null;

    private ?string $description = null;

    private ?string $canonical = null;

    private string $type = 'website';

    private ?string $image = null;

    private ?string $imageAlt = null;

    /** @var array<int, array<string, mixed>> */
    private array $jsonLd = [];

    private bool $noindex = false;

    public static function make(): self
    {
        return new self;
    }

    public function title(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function canonical(?string $canonical): self
    {
        $this->canonical = $canonical;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function image(?string $url, ?string $alt = null): self
    {
        $this->image = $url;
        $this->imageAlt = $alt;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function jsonLd(array $data): self
    {
        $this->jsonLd[] = $data;

        return $this;
    }

    public function noindex(bool $flag = true): self
    {
        $this->noindex = $flag;

        return $this;
    }

    /**
     * Produce the fully resolved SEO payload for the layout.
     *
     * @param  array<string, string|null>  $settings
     * @return array<string, mixed>
     */
    public function resolve(array $settings): array
    {
        $siteName = $settings['name'] ?? config('app.name');
        $professionalTitle = $settings['professional_title'] ?? 'Software Developer';
        $defaultTitle = "{$siteName} — {$professionalTitle}";
        $defaultDescription = $settings['short_bio']
            ?? 'Software developer building web, mobile, AI and automation products.';

        $image = $this->image ?? $this->imageFromSettings($settings);

        return [
            'title' => $this->title ? "{$this->title} — {$siteName}" : $defaultTitle,
            'description' => $this->description ?? $defaultDescription,
            'canonical' => $this->canonical ?? url()->current(),
            'type' => $this->type,
            'image' => $image,
            'imageAlt' => $this->imageAlt ?? $siteName,
            'siteName' => $siteName,
            'twitterHandle' => $this->resolveTwitterHandle($settings),
            'jsonLd' => $this->jsonLd,
            'noindex' => $this->noindex,
        ];
    }

    /**
     * @param  array<string, string|null>  $settings
     */
    private function imageFromSettings(array $settings): ?string
    {
        if (! empty($settings['profile_image'])) {
            return url(Storage::disk('public')->url($settings['profile_image']));
        }

        if (file_exists(public_path('og-default.jpg'))) {
            return url('og-default.jpg');
        }

        return null;
    }

    /**
     * @param  array<string, string|null>  $settings
     */
    private function resolveTwitterHandle(array $settings): ?string
    {
        $url = $settings['twitter_url'] ?? null;

        if (blank($url)) {
            return null;
        }

        if (preg_match('~(?:twitter\.com|x\.com)/([A-Za-z0-9_]+)~', $url, $m)) {
            return '@'.$m[1];
        }

        return null;
    }
}
