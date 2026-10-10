<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\AccountSettings;
use App\Livewire\Admin\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_account_settings_page(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/account')
            ->assertOk()
            ->assertSee('Login email')
            ->assertSee('Current password');
    }

    public function test_admin_can_update_login_email_display_name_and_password(): void
    {
        $user = User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'current-secret',
        ]);

        $this->actingAs($user);

        Livewire::test(AccountSettings::class)
            ->set('name', 'Portfolio Owner')
            ->set('email', 'owner@example.test')
            ->set('current_password', 'current-secret')
            ->set('password', 'new-password-123')
            ->set('password_confirmation', 'new-password-123')
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertSame('Portfolio Owner', $user->name);
        $this->assertSame('owner@example.test', $user->email);
        $this->assertTrue(Hash::check('new-password-123', $user->password));

        $this->post('/admin/logout');

        Livewire::test(Login::class)
            ->set('email', 'owner@example.test')
            ->set('password', 'new-password-123')
            ->call('login')
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_email_can_be_changed_without_changing_the_password(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@example.test',
            'password' => 'current-secret',
        ]);

        $this->actingAs($user);

        Livewire::test(AccountSettings::class)
            ->set('email', 'owner@example.test')
            ->set('current_password', 'current-secret')
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertSame('owner@example.test', $user->email);
        $this->assertTrue(Hash::check('current-secret', $user->password));
    }

    public function test_account_changes_require_the_current_password(): void
    {
        $user = User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'current-secret',
        ]);

        $this->actingAs($user);

        Livewire::test(AccountSettings::class)
            ->set('name', 'Changed Name')
            ->set('email', 'changed@example.test')
            ->set('current_password', 'incorrect-secret')
            ->set('password', 'new-password-123')
            ->set('password_confirmation', 'new-password-123')
            ->call('save')
            ->assertHasErrors('current_password');

        $this->assertSame('Admin', $user->fresh()->name);
        $this->assertSame('admin@example.test', $user->fresh()->email);
        $this->assertTrue(Hash::check('current-secret', $user->fresh()->password));
    }
}
