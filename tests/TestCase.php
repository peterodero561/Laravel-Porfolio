<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Never persist uploaded files across tests.
        Storage::fake('public');

        // Always start with a clean cache.
        Cache::flush();
    }

    protected function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    protected function nonAdmin(): User
    {
        return User::factory()->create(['is_admin' => false]);
    }
}
