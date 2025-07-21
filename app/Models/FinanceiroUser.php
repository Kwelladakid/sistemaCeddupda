<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FinanceiroUser extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
