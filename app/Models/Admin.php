<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class Admin extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes;
    protected $guard_name = 'admin';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'is_deleted',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
