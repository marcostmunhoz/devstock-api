@extends('layouts.app')

@section('content')
<div class="container">
    <div class="line">
        <label for="tp_movimentacao">Tipo de Movimentação: </label>
        <select id="tp_movimentacao">
            <option value="1">Entrada</option>
            <option value="2">Saída</option>
        </select>
    </div>
    <div class="line">
        <label for="ds_movimentacao">Descrição: </label>
        <input type="text" id="ds_movimentacao">
    </div>
    <div class="line">
        <label for="id_produto">Produto</label>
        <select id="id_produto">
            
        </select>
    </div>
@endsection