<?php

namespace App;

class Movimentacao extends CustomModel
{
    const UPDATED_AT = null;

    protected $primaryKey = 'id_movimentacao';
    protected $table = 'movimentacoes';
    protected $fillable = [
        'tp_movimentacao', 'dthr_movimentacao', 'ds_movimentacao', 'id_usuario'
    ];

    public function produtosMovimentacao() {
        return $this->hasMany(ProdutoMovimentacao::class, 'id_movimentacao', 'id_movimentacao')->with('produto');
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}
