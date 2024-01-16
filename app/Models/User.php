<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, UserRole::class);
    }

    public function applies()
    {
        return $this->belongsToMany(
            Shift::class,
            ShiftApplication::class,
        )->withPivot(['id', 'user_id', 'shift_id', 'data']);
    }

    public function isManager()
    {
        return app(RoleService::class)->hasPermission(
            join('|', [
                Str::lower(RoleEnum::MANAGER->name),
                Str::lower(RoleEnum::HEADQUARTER->name),
            ])
        );
    }
}
