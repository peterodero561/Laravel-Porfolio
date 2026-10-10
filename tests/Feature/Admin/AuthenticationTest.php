<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_loads(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Sign in');
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_guest_is_redirected_from_every_admin_route(): void
    {
        $routes = [
            '/admin',
            '/admin/projects',
            '/admin/projects/create',
            '/admin/skills',
            '/admin/experience',
            '/admin/services',
            '/admin/messages',
            '/admin/account',
            '/admin/settings',
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertRedirect('/admin/login');
        }
    }

    public function test_non_admin_is_forbidden(): void
    {
        $this->actingAs($this->nonAdmin());

        $this->get('/admin')->assertForbidden();
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.test',
            'password' => bcrypt('secret-pass'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'admin@example.test')
            ->set('password', 'secret-pass')
            ->call('login')
            ->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.test',
            'password' => bcrypt('secret-pass'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'admin@example.test')
            ->set('password', 'wrong')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_user_cannot_login_even_with_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@example.test',
            'password' => bcrypt('secret-pass'),
            'is_admin' => false,
        ]);

        Livewire::test(Login::class)
            ->set('email', 'user@example.test')
            ->set('password', 'secret-pass')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        RateLimiter::clear('admin-login:127.0.0.1');

        User::factory()->admin()->create([
            'email' => 'admin@example.test',
            'password' => bcrypt('secret-pass'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Login::class)
                ->set('email', 'admin@example.test')
                ->set('password', 'wrong')
                ->call('login');
        }

        // The 6th attempt is blocked even with the right password.
        Livewire::test(Login::class)
            ->set('email', 'admin@example.test')
            ->set('password', 'secret-pass')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_clears_session(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/logout')->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}
