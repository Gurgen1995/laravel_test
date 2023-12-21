<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use CoreProc\WalletPlus\Models\Traits\HasWallets;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasWallets;

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

    public function transactions()
    {
        return $this->hasMany(UserWallet::class);
    }
    public function validTransactions()
    {
    return $this->transactions()->where('status', 1);
    }

    public function credit()
    {
    return $this->validTransactions()
        ->where('type', 'credit')
        ->sum('amount');
    }
    public function debit()
    {
    return $this->validTransactions()
        ->where('type', 'debit')
        ->sum('amount');
    }

    public function balance()
    {
    return $this->credit() - $this->debit();
    }

    public function allowWithdraw($amount) : bool
    {
    return $this->balance() >= $amount;
    }

}
