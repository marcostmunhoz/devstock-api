<?php

namespace App;

class Telefone extends CustomModel
{
    public $timestamps = false;
    protected $primaryKey = 'id_telefone';
    protected $table = 'telefones';
    protected $fillable = [
        'ddd_telefone',
        'nr_telefone',
        'tp_telefone',
        'id_fornecedor'
    ];

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
