<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Experience\Index as ExperienceIndex;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Messages\Index as MessagesIndex;
use App\Livewire\Admin\Projects\Form as ProjectForm;
use App\Livewire\Admin\Projects\Index as ProjectsIndex;
use App\Livewire\Admin\Services\Index as ServicesIndex;
use App\Livewire\Admin\Settings\Form as SettingsForm;
use App\Livewire\Admin\Skills\Index as SkillsIndex;
use App\Livewire\Public\ContactForm;
use App\Livewire\Public\ProjectIndex;
use Illuminate\Support\Facades\Route;

// Public

Route::view('/', 'pages.home')->name('home');
Route::get('/projects', ProjectIndex::class)->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::get('/contact', ContactForm::class)->name('contact');
Route::view('/resume', 'pages.resume')->name('resume');

// Admin

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', Login::class)->middleware('guest')->name('login');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', Dashboard::class)->name('dashboard');

        Route::get('/projects', ProjectsIndex::class)->name('projects');
        Route::get('/projects/create', ProjectForm::class)->name('projects.create');
        Route::get('/projects/{project}/edit', ProjectForm::class)->name('projects.edit');

        Route::get('/skills', SkillsIndex::class)->name('skills');
        Route::get('/experience', ExperienceIndex::class)->name('experience');
        Route::get('/services', ServicesIndex::class)->name('services');
        Route::get('/messages', MessagesIndex::class)->name('messages');
        Route::get('/settings', SettingsForm::class)->name('settings');
    });
});

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        'Disallow: /admin/',
        '',
        'Sitemap: '.url('/sitemap.xml'),
        '',
    ];

    return response(implode("\n", $lines), 200, [
        'Content-Type' => 'text/plain; charset=utf-8',
    ]);
})->name('robots');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
