<?php

namespace App;

class Produto extends CustomModel
{
    protected $primaryKey = 'id_produto';
    protected $table = 'produtos';

    protected $fillable = [
        'cod_produto', 'nm_produto', 'flg_status', 'nr_qtd_estocada'
    ];

    public function fornecedores() {
        return $this->hasMany(ProdutoFornecedor::class, 'id_produto', 'id_produto')->with('fornecedor');
    }

    public function movimentacoes() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_produto', 'id_produto')->with('movimentacao');
    }
}
