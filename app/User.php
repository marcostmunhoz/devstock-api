<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'dt_cadastro';
    const UPDATED_AT = 'dt_edicao';

    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nm_usuario', 'login', 'password', 'email', 'flg_status', 'flg_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function movimentacoes() {
        return $this->hasMany(Movimentacao::class, 'id_usuario', 'id_usuario');
    }

    public function isAdmin() {
        return $this->flg_admin == 1;
    }
}
