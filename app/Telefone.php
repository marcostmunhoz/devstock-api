<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $primaryKey = 'id_telefone';
    protected $table = 'telefones';

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
