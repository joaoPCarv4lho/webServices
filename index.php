<?php

require_once("./model/Usuario.php");
require_once("./model/Tasks.php");
require_once("./databases/MariaDB.php");

function dd($valor)
{
    echo "<pre>";
    print_r($valor);
    echo "</pre>";
}

$metodo  = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];
$rota = explode('/', $path);

$status_code = 200;
$resposta = [
    "status" => true,
    "mensagem" => "",
];



if ($rota[1] == "usuarios") {
    $database = new MariaDB();
    $usuario = new Usuario($database->dbConnection());
    switch ($metodo) {
        case "GET":
            if (isset($rota[2]) && is_numeric($rota[2])) {
                $result = $usuario->getUserByID($rota[2]);
                if (count($result) == 0) {
                    $status_code = 403;
                    $resposta['status'] = false;
                    $resposta['mensagem'] = "Usuário não encontrado";
                    break;
                }
                $resposta['dados'] = $result;
            } else {
                $resposta['dados'] = $usuario->getAll();
            }
            break;
        case "DELETE":
            if (isset($rota[2]) && is_numeric($rota[2])) {
                $usuario->id = $rota[2];
                $result = $usuario->remove();

                if ($result === false) {
                    $status_code = 403;
                    $resposta['status'] = false;
                    $resposta['mensagem'] = "Erro ao tentar remover o usuário";
                    break;
                }
            } else {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Usuário não foi informado";
            }
            break;
        case "POST":
            $parametros = file_get_contents('php://input');
            $parametros = (array) json_decode($parametros, true);
            $usuario->nome = $parametros['nome'];
            $usuario->login = $parametros['login'];
            $usuario->senha = $parametros['senha'];

            if (!$usuario->create()) {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Erro ao tentar cadastrar o usuário";
                break;
            }
            $resposta['mensagem'] = "Usuário cadastrado com sucesso!";
            break;
        case "PUT":
            $parametros = file_get_contents('php://input');
            $parametros = (array) json_decode($parametros, true);
            $usuario->id = $rota[2];
            $usuario->nome = $parametros['nome'];
            $usuario->login = $parametros['login'];
            $usuario->senha = $parametros['senha'];

            if (!$usuario->update()) {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Erro ao tentar atualizar o usuário";
                break;
            }
            $resposta['mensagem'] = "Usuário atualizado com sucesso!";
            break;
        default:
            $status_code = 403;
            $resposta['status'] = false;
            $resposta['mensagem'] = "Método não permitido";
    }
} else if ($rota[1] == "tarefas") {
    $database = new MariaDB();
    $tarefas = new Tasks($database->dbConnection());
    switch ($metodo) {
        case "GET":
            if (isset($rota[2]) && $rota[2] == "usuarios") {
                $result = $tarefas->getTaskUserByID($rota[3]);
                    if (count($result) == 0) {
                        $status_code = 403;
                        $resposta['status'] = false;
                        $resposta['mensagem'] = "Não foi encontrado";
                    }
                    break;

                $resposta['dados'] = $result;

            } else if (isset($rota[1]) && is_numeric($rota[2])) {
                $result = $tarefas->getTaskByID($rota[2]);
                if (count($result) == 0) {
                    $status_code = 403;
                    $resposta['status'] = false;
                    $resposta['mensagem'] = "Tarefas não encontradas";
                    break;
                }

                $resposta['dados'] = $result;

            } else {
                $resposta['dados'] = $tarefas->getAllTasks();
            }
            break;
        case "DELETE":
            if (isset($rota[2]) && is_numeric($rota[2])) {
                $tarefas->id_tarefa = $rota[2];
                $result = $tarefas->removeTask();

                if ($result === false) {
                    $status_code = 403;
                    $resposta['status'] = false;
                    $resposta['mensagem'] = "Erro ao tentar remover a tarefa";
                    break;
                }
            } else {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Tarefa não informada";
            }
            break;
        case "POST":
            $parametros = file_get_contents('php://input');
            $parametros = (array) json_decode($parametros, true);
            $tarefas->titulo = $parametros['titulo'];
            $tarefas->descricao = $parametros['descricao'];
            $tarefas->id_usuario = $parametros['id_usuario'];

            if (!$tarefas->createTask()) {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Erro ao tentar cadastrar nova tarefa";
                break;
            }
            $resposta['mensagem'] = "Tarefa cadastrada com sucesso!";
            break;
        case "PUT":
            $parametros = file_get_contents('php://input');
            $parametros = (array) json_decode($parametros, true);
            $tarefas->id_tarefa = $rota[2];
            $tarefas->titulo = $parametros['titulo'];
            $tarefas->descricao = $parametros['descricao'];
            $tarefas->id_usuario = $parametros['id_usuario'];

            if (!$tarefas->updateTask()) {
                $status_code = 403;
                $resposta['status'] = false;
                $resposta['mensagem'] = "Erro ao tentar atualizar a tarefa";
                break;
            }
            $resposta['mensagem'] = "Tarefa atualizado com sucesso!";
            break;
        default:
            $status_code = 403;
            $resposta['status'] = false;
            $resposta['mensagem'] = "Método não permitido";
    }
}

http_response_code($status_code);
header("Content-Type: application/json");
echo json_encode($resposta);
