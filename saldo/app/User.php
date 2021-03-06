<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Balance;
use App\Models\History;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    //relacionamento 1:1 user:balance
    public function balance(){
        return $this->hasOne(Balance::class);
    }

    public function histories(){
        return $this->hasMany(History::class);
    }

    public function getSender($sender){
        return $this->where('name', 'LIKE', "%$sender%")->orWhere('email', $sender)->get()->first();
    }
}
