@extends('layout.app')

@section('content')
<label for="id_fornecedor">ID: </label><input type="text" id="id_fornecedor">
<label for="razao_social">Razão Social: </label><input type="text" id="razao_social">
<label for="nome_fantasia">Nome Fantasia: </label><input type="text" id="nome_fantasia">
<label for="cnpj_fornecedor">CNPJ: </label><input type="text" id="cnpj_fornecedor">
<label for="end_fornecedor">End. Fornecedor: </label><input type="text" id="end_fornecedor">
<button type="button" id="btn_cadastrar">Cadastrar</button>
<button type="button" id="btn_editar">Editar</button>
<button type="button" id="btn_excluir">Excluir</button>
<button type="button" id="btn_consultar">Consultar</button>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let idFornecedor = document.querySelector('#id_fornecedor'),
        razaoSocial = document.querySelector('#razao_social'),
        nomeFantasia = document.querySelector('#nome_fantasia'),
        cnpjFornecedor = document.querySelector('#cnpj_fornecedor'),
        endFornecedor = document.querySelector('#end_fornecedor'),
        btnCadastrar = document.querySelector('#btn_cadastrar'),
        btnEditar = document.querySelector('#btn_editar'),
        btnExcluir = document.querySelector('#btn_excluir'),
        btnConsultar = document.querySelector('#btn_consultar');

    
});
</script>
@endsection