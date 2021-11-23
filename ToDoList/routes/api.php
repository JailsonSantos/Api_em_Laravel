<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

// Criando as rotas da API ToDoList
Route::get('/ping', function () {
    return [
        'pong' => true
    ];
});

// Rota de redirect para login Login
Route::get('/unauthenticated', function () {
    return ['error' => 'Usuario não está logado.'];
})->name('login');

// Rota para criar usuario e fazer login
Route::post('/user', [AuthController::class, 'create']);
// Route::post('/auth', [AuthController::class, 'login']);

// Rota de login e logout com JWT
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->get('/auth/me', [AuthController::class, 'me']);

// Rota de Logout com Sanctum
Route::middleware('auth:sanctum')->get('/auth/logout', [AuthController::class, 'logout']);

// Planejamento das rotas da API todo list

// POST     /todolist   = Inserir uma tarefa no sistema
// GET      /todoslists = Ler todas as tarefas o sistema
// GET      /todolist/2 = Ler uma tarefa específica do sistema
// PUT      /todolist/2 = Atualiar uma tarefa específica
// DELETE   /todolist/2 = Deletar uma tarefa específa

// Rotas Publicas
Route::get('/todolists', [ApiController::class, 'readAllToDoLists']);
Route::get('/todolist/{id}', [ApiController::class, 'readToDoList']);


// Rotas Protegidas, por usuarios logados com JWT
Route::middleware('auth:api')->post('/todolist', [ApiController::class, 'createToDoList']);
Route::middleware('auth:api')->put('/todolist/{id}', [ApiController::class, 'updateToDoList']);
Route::middleware('auth:api')->delete('/todolist/{id}', [ApiController::class, 'deleteToDoList']);

// Rotas Protegidas, por usuarios logados com sanctum
// Route::middleware('auth:sanctum')->post('/todolist', [ApiController::class, 'createToDoList']);
// Route::middleware('auth:sanctum')->put('/todolist/{id}', [ApiController::class, 'updateToDoList']);
// Route::middleware('auth:sanctum')->delete('/todolist/{id}', [ApiController::class, 'deleteToDoList']);


// Processo de login via JWT
