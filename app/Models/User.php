<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model
    implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    protected $fillable = [
        'name', 'userid', 'email', 'password',
        'current_branch_id', 'social',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function currentBranch() {
        return $this->belongsTo('App\Models\Branch', 'current_branch_id');
    }

}
