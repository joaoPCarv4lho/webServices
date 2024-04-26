<?php //model/usuario.php

class Usuario
{
    private $conn;
    private $table_name = 'usuarios';
    public $id;

    public $nome;
    public $login;
    public $senha;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create(){
        $query = 'INSERT INTO ' . $this->table_name . ' SET nome = :nome, login = :login, senha = :senha';
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':senha', $this->senha);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function getAll(){
        $query = 'SELECT * FROM ' . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByID(int $id){
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->nome = $row['nome'];
            $this->login = $row['login'];
            $this->senha = $row['senha'];
            return $row;
        }
        return[];
    }


    public function update(){
        $query = 'UPDATE ' . $this->table_name . ' SET nome = :nome, login = :login, senha = :senha WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':id', $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function remove(){
       $query = 'DELETE FROM ' . $this->table_name . ' WHERE id = :id';
       $stmt = $this->conn->prepare($query);

       $this->id = htmlspecialchars(strip_tags($this->id));
       $stmt->bindParam(':id', $this->id);

       if($stmt->execute()){
        return true;
       }
       return false;
    }
}
