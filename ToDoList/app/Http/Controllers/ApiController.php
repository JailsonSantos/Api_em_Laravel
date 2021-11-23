<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Validation
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\Todo;

class ApiController extends Controller
{
    public function createToDoList(Request $request)
    {
        $array = ['error' => ''];

        // Validando com as Regras
        $rules = [
            'title' => 'required|min:3',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
            return $array;
        }

        $title = $request->input('title');
        $done = $request->input('done');

        // Criando o Registro
        $newTodoList = new Todo();
        $newTodoList->title = $title;
        $newTodoList->done = $done;
        $newTodoList->save();

        return $array;
    }

    public function readAllToDoLists()
    {
        $array = ['error' => ''];

        // $array['list'] = Todo::all();

        // Busca com paginação
        // $todos = Todo::simplePagination(2); // Não pega o total de registros encontrados
        // $todos = Todo::where('done',1)->simplePaginate(2); // Paginação com condicional

        $todos = Todo::paginate(2);             // Pega o total de resistros encontrados

        $array['list'] = $todos->items();
        $array['currentPage'] = $todos->currentPage();

        return $array;
    }

    public function readToDoList($id)
    {
        $array = ['error' => ''];

        $todo = Todo::find($id);

        if ($todo) {
            $array['todo'] = $todo;
        } else {
            $array['error'] = 'A tarefa ' . $id . ' não existe.';
        }

        return $array;
    }

    public function updateToDoList($id, Request $request)
    {
        $array = ['error' => ''];

        // Validando os dados 
        $rules = [
            'title' => 'min:3',
            'done' => 'boolean' // true, false, 1, 0, '1', '0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
        }

        $title = $request->input('title');
        $done = $request->input('done');

        // Atualizando o item
        $todo = Todo::find($id);

        if ($todo) {

            if ($title) {
                $todo->title = $title;
            }
            if ($done !== NULL) {
                $todo->done = $done;
            }

            $todo->save();
        } else {
            $array['error'] = 'Tarefa ' . $id . ' ão existe, logo não pode ser atualizada.';
        }


        return $array;
    }

    public function deleteToDoList($id)
    {
        $array = ['error' => ''];

        $todo = Todo::find($id);

        if ($todo) {
            $todo->delete();
        } else {
            $array['error'] = 'Tarefa ' . $id . ' não existe, logo não pode ser deletada.';
        }

        /*  try {
                Todo::where('id', $id)->delete();
            } catch (\Exception $e) {
                $array['error'] = 'Tarefa ' . $id . ' não existe, logo não pode ser deletada.';
            }
        */

        // Opção 1
        // $todo = Todo::find($id);
        // $todo->delete();

        // Opção 2
        // $todo->display($id);

        return $array;
    }
}
