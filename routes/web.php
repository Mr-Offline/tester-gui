<?php

use App\Livewire\Main;
use App\Livewire\Projects\Show;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', Main::class)->name('home');

Route::get('/projects/{project}', Show::class)
    ->missing(fn() => to_route('home'))
    ->name('projects.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::fallback(function () {
    return to_route('home');
});

require __DIR__.'/auth.php';
