<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Movimentacao;
use App\ProdutoMovimentacao;

class EstoqueController extends Controller
{
    public function __construct() {
        $this->insertRules = [
            'tp_movimentacao'    => 'required|integer|in:1,2',
            'ds_movimentacao'    => 'required|string|max:50',
            'id_produto'         => 'required|integer|exists:produtos,id_produto',
            'nr_qtd_movimentada' => 'required|integer|min:1',
            'vlr_unitario'       => 'required|numeric|min:0'
        ]; 
    }

    public function listarMovimentacoes($idProduto) {
        $produto = Produto::find($idProduto);

        if (!$produto || $produto->flg_status == 2) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produto não encontrado'
            ], 404);
        }

        $movimentacoes = Movimentacao::select('movimentacoes.*')
                                    ->join('produtos_movimentacoes', 'movimentacoes.id_movimentacao', '=', 'produtos_movimentacoes.id_movimentacao')
                                    ->where('id_produto', '=', $idProduto)
                                    ->orderBy('dthr_movimentacao', 'ASC')
                                    ->with('produtosMovimentacao')
                                    ->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $movimentacoes
        ]);
    }

    public function realizarMovimentacao(Request $request) {
        try {
            $data = $this->validateWith($this->insertRules, $request);

            $produto = Produto::find($data['id_produto']);
            if (!$produto || $produto->flg_status == 2) {
                return response::json([
                    'status'  => 'error',
                    'message' => 'Produto não encontrado'
                ], 404);
            } elseif ($data['tp_movimentacao'] == 2 && $produto->nr_qtd_estocada < $data['nr_qtd_movimentada']) {
                return response()->json([
                    'status' => 'error',
                    'message' => "O produto $produto->cod_produto | $produto->nm_produto não possui quantidade suficiente em estoque",
                    'data' => [
                        'nr_qtd_estocada' => $produto->nr_qtd_estocada
                    ]
                ]);
            }

            \DB::beginTransaction();
            $movimentacao = Movimentacao::create([
                'tp_movimentacao'   => $data['tp_movimentacao'],
                'ds_movimentacao'   => $data['ds_movimentacao'],
                'id_usuario'        => auth()->id(),
                'dthr_movimentacao' => array_key_exists('dthr_movimentacao', $data) ? $data['dthr_movimentacao'] : date('Y-m-d H:i:s')
            ]);

            ProdutoMovimentacao::create([
                'id_produto'         => $produto->id_produto,
                'id_movimentacao'    => $movimentacao->id_movimentacao,
                'nr_qtd_movimentada' => $data['nr_qtd_movimentada'],
                'vlr_unitario'       => $data['vlr_unitario']
            ]);

            if ($data['tp_movimentacao'] == 1) {
                $produto->nr_qtd_estocada += $data['nr_qtd_movimentada'];
            } else {
                $produto->nr_qtd_estocada -= $data['nr_qtd_movimentada'];
            }

            $produto->save();
            \DB::commit();
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ocorreu um erro na validação.',
                'data'    => $ex->validator->getMessageBag()->toArray()
            ], 400);
        } catch (\Exception $ex) {
            \DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ]);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Movimentação realizada com sucesso.'
        ]);
    }
}
