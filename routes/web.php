<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {

    Route::view('/login', 'login')->name('login');

    Route::view('/register', 'register')->name('register');

});

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::view('/customers', 'customers')->name('customers');

    Route::view('/leads', 'leads')->name('leads');

    Route::view('/deals', 'deals')->name('deals');

    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
