<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $primaryKey = 'id_produto';
    protected $table = 'produtos';

    public function fornecedores() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_produto', 'id_produto')->with('fornecedor');
    }

    public function movimentacoes() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_produto', 'id_produto')->with('movimentacao');
    }
}
