<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Admin extends User
{
    use HasFactory;

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'address', 'domain', 'password', 'show_password', 'status', 'main_account', 'site_greetings', 'site_link', 'renewed_at', 'expired_at'
    ];
}
