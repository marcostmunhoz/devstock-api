<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('nm_usuario', 50);
            $table->string('login', 20)->unique();
            $table->string('senha', 60);
            $table->string('email', 191)->unique();
            $table->boolean('flg_status')->default(1);
            $table->rememberToken();
            $table->timestamp('dt_cadastro')->nullable();
            $table->timestamp('dt_edicao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
