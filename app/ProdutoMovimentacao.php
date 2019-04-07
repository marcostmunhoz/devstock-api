<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutoMovimentacao extends Model
{
    protected $primaryKey = 'id_produto_movimentacao';
    protected $table = 'produtos_movimentacao';

    public function produto() {
        return $this->hasOne(Produto::class, 'id_produto', 'id_produto');
    }

    public function movimentacao() {
        return $this->hasOne(Movimentacao::class, 'id_movimentacao', 'id_movimentacao');
    }
}
