<?php

use Illuminate\Support\Facades\Route;
use Filament\Pages\Auth\Login;

Route::get('/', function () {
    return redirect('admin/login');
});

Route::post('/admin/login', [Login::class, 'authenticate'])->name('filament.admin.auth.login');
