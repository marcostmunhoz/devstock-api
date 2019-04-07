<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $primaryKey = 'id_fornecedor';
    protected $table = 'fornecedores';

    public function telefones() {
        return $this->hasMany(Telefone::class, 'id_fornecedor', 'id_fornecedor');
    }

    public function produtos() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_fornecedor', 'id_fornecedor')->with('produto');
    }
}
