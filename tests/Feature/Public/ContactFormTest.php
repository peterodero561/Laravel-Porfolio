<?php

namespace Tests\Feature\Public;

use App\Jobs\SendContactNotification;
use App\Livewire\Public\ContactForm;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Notifications\ContactMessageReceived;
use App\Services\PortfolioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_loads(): void
    {
        $this->get('/contact')->assertOk()->assertSee('Contact');
    }

    public function test_form_validates_required_fields(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', '')
            ->set('email', '')
            ->set('subject', '')
            ->set('message', '')
            ->call('submit')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'required',
                'subject' => 'required',
                'message' => 'required',
            ]);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_form_validates_email_format(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Test')
            ->set('email', 'not-an-email')
            ->set('subject', 'Hi')
            ->set('message', 'A message that is long enough.')
            ->call('submit')
            ->assertHasErrors(['email']);
    }

    public function test_form_validates_message_minimum_length(): void
    {
        Livewire::test(ContactForm::class)
            ->set('name', 'Test')
            ->set('email', 'test@example.test')
            ->set('subject', 'Hi')
            ->set('message', 'short')
            ->call('submit')
            ->assertHasErrors(['message' => 'min']);
    }

    public function test_valid_submission_stores_message_and_dispatches_job(): void
    {
        Queue::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.test')
            ->set('subject', 'Project enquiry')
            ->set('message', 'I have a project I want to discuss with you.')
            ->call('submit')
            ->assertSet('sent', true)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.test',
            'subject' => 'Project enquiry',
        ]);

        Queue::assertPushed(SendContactNotification::class, function ($job) {
            return $job->message->email === 'jane@example.test';
        });
    }

    public function test_form_resets_after_successful_submission(): void
    {
        Queue::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Jane')
            ->set('email', 'jane@example.test')
            ->set('subject', 'Hi')
            ->set('message', 'A valid message with enough length.')
            ->call('submit')
            ->assertSet('name', '')
            ->assertSet('email', '')
            ->assertSet('subject', '')
            ->assertSet('message', '');
    }

    public function test_job_sends_notification_to_admin_email(): void
    {
        Notification::fake();

        SiteSetting::set('email', 'admin@example.test');

        $message = ContactMessage::create([
            'name' => 'Jane',
            'email' => 'jane@example.test',
            'subject' => 'Hi',
            'message' => 'A message.',
        ]);

        (new SendContactNotification($message))
            ->handle(app(PortfolioService::class));

        Notification::assertSentOnDemand(
            ContactMessageReceived::class,
            fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'admin@example.test',
        );
    }

    public function test_message_is_stored_even_if_notification_fails(): void
    {
        // Force the notification to throw.
        Notification::shouldReceive('route')
            ->zeroOrMoreTimes()
            ->andReturnSelf();

        Notification::shouldReceive('send')
            ->zeroOrMoreTimes()
            ->andThrow(new \RuntimeException('Mail server down'));

        $this->expectException(\RuntimeException::class);

        try {
            Livewire::test(ContactForm::class)
                ->set('name', 'Jane')
                ->set('email', 'jane@example.test')
                ->set('subject', 'Hi')
                ->set('message', 'A valid message with enough length.')
                ->call('submit');
        } finally {
            // The message row must exist regardless of the notification outcome.
            $this->assertDatabaseHas('contact_messages', [
                'email' => 'jane@example.test',
            ]);
        }
    }
}
