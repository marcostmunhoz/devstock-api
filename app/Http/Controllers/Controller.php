<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $insertRules = [];
    protected $updateRules = [];
    protected $searchColumns = [];
    protected $relations = [];
    protected $useStatusFlag = false;
    protected $model;
    protected $friendlyName;
    protected $afterInsert = null;

    public function showAll($includeRelations = false) {
        $result = null;

        if ($this->useStatusFlag) {
            if ($includeRelations && count($this->relations)) {
                $result = $this->model::with($this->relations)->where('flg_status', '!=', 2)->get();
            } else {
                $result = $this->model::where('flg_status', '!=', 2)->get();
            }
        } else {
            if ($includeRelations && count($this->relations)) {
                $result = $this->model::with($this->relations)->all();
            } else {
                $result = $this->model::all();
            }
        }

        return response()->json([
            'status' => 'ok',
            'data'   => $result
        ]);
    }

    public function show($id, $includeRelations = false) {
        $result = null;

        try {
            if ($includeRelations && count($this->relations)) {
                $result = $this->model::with($this->relations)->find($id);
            } else {
                $result = $this->model::find($id);
            }

            if (!$result || ($this->useStatusFlag && $result->flg_status == 2)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$this->friendlyName não encontrado(a)."
                ], 404);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([ 
            'status' => 'ok',
            'data'   => $result 
        ]);
    }

    public function searchLike($q, $includeRelations = false) {
        $columns = $this->searchColumns;
        $result = null;

        if (!$q) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Nenhumo dado fornecido para a busca.'
            ], 400);
        } elseif (!count($columns)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foram especificadas as colunas para a busca.'
            ], 500);
        }

        $result = $this->model::where(function($query) use ($q, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%$q%");
            }
        });

        if ($this->useStatusFlag) {
            $result->where('flg_status', '=', 1);
        }

        if ($includeRelations && count($this->relations)) {
            $result = $result->with($this->relations);
        }

        $result = $result->get();

        return response()->json([
            'status' => 'ok',
            'data'   => $result
        ]);
    }

    public function create(Request $request) {
        $result = null;

        try {
            $fields = $this->validateWith($this->insertRules, $request);

            \DB::beginTransaction();
            $result = new $this->model;
            $result->fill($fields);
            $result->save();

            if ($this->afterInsert && is_callable(($this->afterInsert))) {
                call_user_func($this->afterInsert, $request, $result);
            }
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
            ], 400);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => "$this->friendlyName cadastrado(a) com sucesso.",
            'data'    => $result
        ]);
    }

    public function update(Request $request, $id) {
        $result = null;

        try {
            $fields = $this->validateWith($this->updateRules, $request);

            $result = $this->model::find($id);
            
            if (!$result || ($this->useStatusFlag && $result->flg_status == 2)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$this->friendlyName não encontrado(a)."
                ], 404);
            }

            $result->fill($fields);
            $result->save();
        } catch (\Illuminate\Validation\ValidationException $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ocorreu um erro na validação.',
                'data'    => $ex->validator->getMessageBag()->toArray()
            ], 400);
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }

        return response()->json([
            'status'  => 'ok',
            'message' => "$this->friendlyName editado(a) com sucesso.",
            'data'    => $result
        ]);
    }

    public function delete($id) {
        try {
            $result = $this->model::find($id);
            
            if (!$result || ($this->useStatusFlag && $result->flg_status == 2)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$this->friendlyName não encontrado(a)."
                ], 404);
            }

            if ($this->useStatusFlag) {
                $result->flg_status = 2;
                $result->save();
            } else {
                $result->delete();
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status'  => 'error',
                'message' => $ex->getMessage()
            ], 400);
        }
        
        return response()->json([
            'status'  => 'ok',
            'message' => "$this->friendlyName excluído(a) com sucesso."
        ]);
    }
}
