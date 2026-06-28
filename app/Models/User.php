<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'is_active',
        'is_deleted',
        'email_verified_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function isGoogleAccount(): bool
    {
        return ! is_null($this->google_id);
    }
    public function hasPassword(): bool
    {
        return ! is_null($this->password);
    }
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
