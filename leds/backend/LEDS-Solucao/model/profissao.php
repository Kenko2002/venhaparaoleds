<?php

include_once "./../../conexao.php";

class Profissao {
    private $id;
    private $nome;

    // Construtor
    public function __construct() {
        $this->id = 0;
        $this->nome="null";
    }

    

    // Métodos Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    // Métodos Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }




    //Funções de Repository para manipulação de dados.
    
    public function create() {
        global $conn;
        if(empty($this->nome)) {
            return false; // Se o nome estiver vazio, retorna false
        }
        
        // Verificar se a profissão já existe no banco de dados
        $sql_check = "SELECT id FROM profissao WHERE nome = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $this->nome);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        // Se um registro for encontrado, definir o ID e retornar true
        if($result_check->num_rows > 0) {
            $row = $result_check->fetch_assoc();
            $this->setId($row['id']);
            return true;
        }
        
        // Caso contrário, inserir uma nova profissão
        $sql_insert = "INSERT INTO profissao (nome) VALUES (?)";
        $stmt_insert = $conn->prepare($sql_insert);
        
        $stmt_insert->bind_param("s", $this->nome);
        
        if($stmt_insert->execute()) {
            $this->setId(mysqli_insert_id($conn));
            return true; 
        } else {
            return false; 
        }
    }
    

    public function update() {
        global $conn;
        $sql = "UPDATE profissao
        SET  
            nome = ?
        WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("si", $this->nome, $this->id);

        $results = $stmt->execute();

        
        return $results;
    }

    public function delete() {
        global $conn;
        $sql="DELETE FROM profissao WHERE id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $results = $stmt->execute();
        return $results;
    }

    public function getAll() {
        global $conn;
        $sql = "SELECT * FROM profissao";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->get_result();
        
        $data = array();
        while ($row = $results->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data; 
    }

    public function getById() {
        global $conn;
        $sql = "SELECT * FROM profissao WHERE id= ?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $results = $stmt->get_result();
        
        
        $data = array();
        while ($row = $results->fetch_assoc()) {
            $data[] = $row;
        }
        
        if( isset($data[0]) ){
            return $data[0]; 
        }else{
            return "Entidade nao encontrada";
        }
        
    }


}