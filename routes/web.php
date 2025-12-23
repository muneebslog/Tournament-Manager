<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Volt::route('/scores', 'matchlist')->name('match.list');
Volt::route('/score/{match}', 'scoreboard')->name('match.scoreboard');




Route::middleware(['auth'])->group(function () {
    // APP ROUTES:
    Volt::route('/events/{id}', 'showevents')->name('events.show');
    Volt::route('/match/{match}', 'matchcontrolpanel')->name('match.control');
    Volt::route('/event/{eventid}/players', 'manageplayers')->name('event.players');
    Volt::route('/event/{eventid}/matches', 'managematches')->name('event.matches');
    Volt::route('/match/generate/{event}', 'matchesgenerator')->name('match.generator');
    Volt::route('/match/detail/{match}', 'matchdetails')->name('match.details');




    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
