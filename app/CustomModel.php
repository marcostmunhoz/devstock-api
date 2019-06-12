<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model {
    const CREATED_AT = 'dt_cadastro';
    const UPDATED_AT = 'dt_edicao';

    public function getDtCadastroAttribute($value) {
        return \Carbon\Carbon::parse($value, 'UTC')->timezone('America/Sao_Paulo');
    }

    public function getDtEdicaoAttribute($value) {
        return \Carbon\Carbon::parse($value, 'UTC')->timezone('America/Sao_Paulo');
    }
}