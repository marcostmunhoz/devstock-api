<?php

namespace App;

class ProdutoMovimentacao extends CustomModel
{
    public $timestamps = false;
    protected $primaryKey = 'id_produto_movimentacao';
    protected $table = 'produtos_movimentacoes';
    protected $fillable = [
        'id_produto', 'id_movimentacao', 'nr_qtd_movimentada', 'vlr_unitario'
    ];

    public function produto() {
        return $this->hasOne(Produto::class, 'id_produto', 'id_produto');
    }

    public function movimentacao() {
        return $this->hasOne(Movimentacao::class, 'id_movimentacao', 'id_movimentacao');
    }
}
