<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::view('/customers', 'customers')->name('customers');

Route::view('/leads', 'leads')->name('leads');