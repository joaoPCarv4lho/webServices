<?php

class Tasks
{
    private $conn;
    private $table_name = 'tarefas';
    public $id_tarefa;
    public $id_usuario;

    public $titulo;
    public $descricao;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createTask(){
        $query = 'INSERT INTO ' . $this->table_name . ' SET titulo = :titulo, descricao = :descricao, id_usuario = :id_usuario';
        $stmt = $this->conn->prepare($query);
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':id_usuario', $this->id_usuario);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function getAllTasks(){
        $query = 'SELECT * FROM ' . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskByID(int $id_tarefa){
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE id_tarefa = :id_tarefa';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_tarefa', $id_tarefa);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->titulo = $row['titulo'];
            $this->descricao = $row['descricao'];
            return $row;
        }
        return[];
    }

    public function getTaskUserByID(int $id_usuario){
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE id_usuario = :id_usuario';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($row) {
            $this->titulo = $row['titulo'];
            $this->descricao = $row['descricao'];
            $this->id_usuario = $row['id_usuario'];
            return $row;
        }
        return[];
    }

    public function updateTask(){
        $query = 'UPDATE ' . $this->table_name . ' SET titulo = :titulo, descricao = :descricao, id_usuario = :id_usuario WHERE id_tarefa = :id_tarefa';
        $stmt = $this->conn->prepare($query);
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->id_tarefa = htmlspecialchars(strip_tags($this->id_tarefa));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':id_tarefa', $this->id_tarefa);
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function removeTask(){
        $query = 'DELETE FROM ' . $this->table_name . ' WHERE id_tarefa = :id_tarefa';
        $stmt = $this->conn->prepare($query);
 
        $this->id_tarefa = htmlspecialchars(strip_tags($this->id_tarefa));
        $stmt->bindParam(':id_tarefa', $this->id_tarefa);
 
        if($stmt->execute()){
         return true;
        }
        return false;
     }
}