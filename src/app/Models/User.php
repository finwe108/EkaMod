<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'employee_id_ref',
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'student_id',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'must_change_password' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id_ref');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole(string $roleName): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('name', $roleName);
        }

        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(array $roleNames): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->whereIn('name', $roleNames)->isNotEmpty();
        }

        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    public function displayRoles(): string
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->pluck('display_name')->join(', ');
        }

        return $this->roles()->pluck('display_name')->join(', ');
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->name ?? 'User'));
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials ?: 'U';
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}