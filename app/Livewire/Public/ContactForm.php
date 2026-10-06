<?php

namespace App\Livewire\Public;

use App\Jobs\SendContactNotification;
use App\Models\ContactMessage;
use App\Support\Seo;
use Illuminate\Support\Str;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function submit(): void
    {
        $this->sent = false;

        $validated = $this->validate();

        $contactMessage = ContactMessage::create([
            ...$validated,
            'ip_address' => request()->ip(),
            'user_agent' => Str::limit((string) request()->userAgent(), 255, ''),
        ]);

        SendContactNotification::dispatch($contactMessage);

        $this->reset('name', 'email', 'subject', 'message');
        $this->resetValidation();
        $this->sent = true;
    }

    public function render()
    {
        $seo = Seo::make()
            ->title('Contact')
            ->description('Get in touch to discuss a project.')
            ->canonical(route('contact'));

        return view('livewire.public.contact-form')
            ->layout('components.layouts.public', ['seo' => $seo]);
    }
}
