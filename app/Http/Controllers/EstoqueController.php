<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Movimentacao;
use App\ProdutoMovimentacao;
use Illuminate\Support\Carbon;

class EstoqueController extends Controller
{
    public function __construct() {
        $this->insertRules = [
            'tp_movimentacao'       => 'required|integer|in:1,2',
            'ds_movimentacao'       => 'required|string|max:50',
            'produtos_movimentacao' => 'required|array',
            'produtos_movimentacao.*.id_produto'         => 'required|integer|exists:produtos,id_produto',
            'produtos_movimentacao.*.nr_qtd_movimentada' => 'required|integer|min:1',
            'produtos_movimentacao.*.vlr_unitario'       => 'required|numeric|min:0'
        ]; 
    }

    public function listarMovimentacoes() {
        $movimentacoes = Movimentacao::with('usuario')
                                    ->orderBy('dthr_movimentacao', 'DESC')
                                    ->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $movimentacoes
        ]);
    }

    public function showMovimentacao($id) {
        $movimentacao = Movimentacao::with([ 'produtosMovimentacao', 'usuario' ])
                                    ->find($id);

        if (!$movimentacao) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Movimentação não encontrada.'
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $movimentacao
        ]);
    }

    public function realizarMovimentacao(Request $request) {
        try {
            $data = $this->validateWith($this->insertRules, $request);

            \DB::beginTransaction();
            $movimentacao = Movimentacao::create([
                'tp_movimentacao'   => $data['tp_movimentacao'],
                'ds_movimentacao'   => $data['ds_movimentacao'],
                'id_usuario'        => auth()->id(),
                'dthr_movimentacao' => array_key_exists('dthr_movimentacao', $data) ? $data['dthr_movimentacao'] : date('Y-m-d H:i:s')
            ]);

            if (count($data['produtos_movimentacao']) == 0) {
                throw new Exception('Nenhum produto submetido.');
            }

            foreach ($data['produtos_movimentacao'] as $prod) {
                $produto = Produto::find($prod['id_produto']);
                if (!$produto || $produto->flg_status == 2) {
                    return response::json([
                        'status'  => 'error',
                        'message' => 'Produto não encontrado'
                    ], 404);
                } elseif ($data['tp_movimentacao'] == 2 && $produto->nr_qtd_estocada < $prod['nr_qtd_movimentada']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "O produto $produto->cod_produto | $produto->nm_produto não possui quantidade suficiente em estoque",
                        'data' => [
                            'nr_qtd_estocada' => $produto->nr_qtd_estocada
                        ]
                    ], 400);
                }

                ProdutoMovimentacao::create([
                    'id_produto'         => $produto->id_produto,
                    'id_movimentacao'    => $movimentacao->id_movimentacao,
                    'nr_qtd_movimentada' => $prod['nr_qtd_movimentada'],
                    'vlr_unitario'       => $prod['vlr_unitario']
                ]);

                if ($data['tp_movimentacao'] == 1) {
                    $produto->nr_qtd_estocada += $prod['nr_qtd_movimentada'];
                } else {
                    $produto->nr_qtd_estocada -= $prod['nr_qtd_movimentada'];
                }

                $produto->save();
            }

            \DB::commit();
        } catch (\Illuminate\Validation\ValidationException $ex) {
            \DB::rollback();
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
            ], 500);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Movimentação realizada com sucesso.',
            'data' => $movimentacao->refresh()->load([ 'usuario', 'produtosMovimentacao' ])
        ]);
    }
}
