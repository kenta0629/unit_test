<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * hasMany
     *
     * @return void
     */
    public function tasks()
    {
        return $this->hasMany('App\Model\Task');
    }
}
