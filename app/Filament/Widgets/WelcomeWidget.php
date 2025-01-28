<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    // Menentukan view yang akan digunakan
    protected static string $view = 'filament.widgets.welcome-widget';
    
    // Mengatur widget agar memanjang penuh
    protected int | string | array $columnSpan = 'full';
    
    // Mengatur urutan widget (opsional)
    protected static ?int $sort = 1;
}