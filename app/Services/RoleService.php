<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\UserRole;

class RoleService
{
    public static function roles()
    {
        return [
            'manager' => RoleEnum::MANAGER->value,
            'teacher' => RoleEnum::TEACHER->value,
            'headquarter' => RoleEnum::HEADQUARTER->value,
        ];
    }
    public function hasPermission($code): bool
    {
        $remapRole = self::roles();
        $roleCodes = explode('|', $code);
        $mappedRoleCodes = array_map(fn($role) => $remapRole[$role] ?? null, $roleCodes);
        $user = auth()->user();
        if (empty($user) || $user->roles->isEmpty()) {
            return false;
        }

        return $user->roles->filter(fn($role) => in_array($role->role, $mappedRoleCodes))->count();
    }
}
