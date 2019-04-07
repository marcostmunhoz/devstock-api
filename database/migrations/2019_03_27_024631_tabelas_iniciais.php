<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelasIniciais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->increments('id_fornecedor');
            $table->string('razao_social', 100);
            $table->string('nome_fantasia', 100);
            $table->string('cnpj_fornecedor', 14)->unique();
            $table->string('end_fornecedor', 150);
            $table->boolean('flg_status')->default(1);
            $table->timestamp('dt_cadastro')->nullable();
            $table->timestamp('dt_edicao')->nullable();
        });

        Schema::create('telefones', function (Blueprint $table) {
            $table->increments('id_telefone');
            $table->string('ddd_telefone', 2);
            $table->string('nr_telefone', 9);
            $table->enum('tp_telefone', [ 1, 2, 3 ])->comment('1 = Comercial, 2 = Contato, 3 = Outro');
            $table->unsignedInteger('id_fornecedor', false);
            $table->foreign('id_fornecedor')->references('id_fornecedor')->on('fornecedores');
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id_produto');
            $table->string('cod_produto', 15)->unique();
            $table->string('nm_produto', 50);
            $table->boolean('flg_status')->default(1);
            $table->unsignedInteger('nr_qtd_estocada', false);
            $table->timestamp('dt_cadastro')->nullable();
            $table->timestamp('dt_edicao')->nullable();
        });

        Schema::create('produtos_fornecedor', function (Blueprint $table) {
            $table->unsignedInteger('id_produto', false);
            $table->unsignedInteger('id_fornecedor', false);
            $table->decimal('nr_preco_compra', 6, 2);
            $table->primary([ 'id_produto', 'id_fornecedor' ]);
            $table->foreign('id_produto')->references('id_produto')->on('produtos');
            $table->foreign('id_fornecedor')->references('id_fornecedor')->on('fornecedores');
        });

        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->increments('id_movimentacao');
            $table->enum('tp_movimentacao', [ 1, 2 ])->comment('1 = entrada, 2 = saída');
            $table->timestamp('dthr_movimentacao')->nullable();
            $table->string('ds_movimentacao', 50);
            $table->unsignedInteger('id_usuario', false);
            $table->timestamp('dt_cadastro')->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('users');
        });

        Schema::create('produtos_movimentacoes', function (Blueprint $table) {
            $table->unsignedInteger('id_produto', false);
            $table->unsignedInteger('id_movimentacao', false);
            $table->unsignedInteger('nr_qtd_movimentada', false);
            $table->decimal('vlr_unitario');
            $table->primary([ 'id_produto', 'id_movimentacao' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_movimentacoes');
        Schema::dropIfExists('movimentacoes');
        Schema::dropIfExists('produtos_fornecedor');
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('telefones');
        Schema::dropIfExists('fornecedores');
    }
}
