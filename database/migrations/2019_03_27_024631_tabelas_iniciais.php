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
            $table->integer('id_fornecedor', 10);
            $table->foreign('id_fornecedor')->references('fornecedor')->on('id_fornecedor');
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id_produto');
            $table->string('cod_produto', 15)->unique();
            $table->string('nm_produto', 50);
            $table->boolean('flg_status')->default(1);
            $table->integer('nr_qtd_estocada');
            $table->timestamp('dt_cadastro')->nullable();
            $table->timestamp('dt_edicao')->nullable();
            $table->integer('id_fornecedor', 10);
            $table->foreign('id_fornecedor')->references('id_fornecedor')->on('fornecedor');
        });

        Schema::create('produtos_fornecedor', function (Blueprint $table) {
            $table->integer('id_produto', 10);
            $table->integer('id_fornecedor', 10);
            $table->decimal('nr_preco_compra', 6, 2);
            $table->primary([ 'id_produto', 'id_fornecedor' ]);
            $table->foreign('id_produto')->references('produtos')->on('id_produto');
            $table->foreign('id_fornecedor')->references('fornecedores')->on('id_fornecedor');
        });

        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->increments('id_movimentacao');
            $table->enum('tp_movimentacao', [ 1, 2 ])->comment('1 = entrada, 2 = saída');
            $table->timestamp('dthr_movimentacao')->nullable();
            $table->string('ds_movimentacao', 50);
            $table->integer('id_usuario', 10);
            $table->timestamp('dt_cadastro')->nullable();
            $table->foreign('id_usuario')->references('usuarios')->on('id_usuario');
        });

        Schema::create('produtos_movimentacoes', function (Blueprint $table) {
            $table->integer('id_produto');
            $table->integer('id_movimentacao');
            $table->integer('nr_qtd_movimentada');
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
