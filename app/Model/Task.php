<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * hasMany
     *
     * @return void
     */
    public function problems()
    {
        return $this->hasMany('App\Model\Problem');
    }
}
