<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id_email';
    protected $table = 'emails';
    protected $fillable = [
        'email',
        'tp_email',
        'id_fornecedor'
    ];

    public function fornecedor() {
        return $this->belongsTo(Fornecedor::class, 'id_fornecedor', 'id_fornecedor');
    }
}
