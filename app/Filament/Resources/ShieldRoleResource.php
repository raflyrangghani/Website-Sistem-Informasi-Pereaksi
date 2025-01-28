<?php

namespace App\Filament\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;

class ShieldRoleResource extends BaseRoleResource
{
    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }

    public static function getNavigationSort(): int
    {
        return 1;
    }
}