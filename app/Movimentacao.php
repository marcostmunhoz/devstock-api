<?php

namespace App;

class Movimentacao extends CustomModel
{
    protected $primaryKey = 'id_movimentacao';
    protected $table = 'movimentacoes';

    public function produtos() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_movimentacao', 'id_movimentacao')->with('produto');
    }

    public function usuario() {
        return $this->belongsTo('users', 'id_usuario', 'id_usuario');
    }
}
