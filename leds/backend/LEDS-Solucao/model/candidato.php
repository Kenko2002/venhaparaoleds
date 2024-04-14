<?php

include_once "./../../conexao.php";

class Candidato {
    private $id;
    private $nome;
    private $cpf;
    private $nascimento;

    // Construtor
    public function __construct() {
        $this->id = 0;
        $this->nome = "null";
        $this->cpf = "null";
        $this->nascimento = "0000/00/00";
    }

    

    // Métodos Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getNascimento() {
        return $this->nascimento;
    }

    // Métodos Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
    }



    //Funções de Repository para manipulação de dados.
    
    public function create() {
        global $conn;
        if(empty($this->nome) || empty($this->cpf) || empty($this->nascimento)) {
            return false; // Se algum dado estiver faltando, retorna false
        }
        $sql = "INSERT INTO candidato (nome, cpf, nascimento) VALUES (?, ?, ?)";
    
        $stmt = $conn->prepare($sql);

        // Supondo que $this->nascimento esteja no formato "YYYY-MM-DD"
        $nascimentoFormatado = date('Y-m-d', strtotime($this->nascimento));


        $stmt->bind_param("sss", $this->nome, $this->cpf,$nascimentoFormatado);
    
        if($stmt->execute()) {
            $this->setId( mysqli_insert_id($conn) );
            return true; 
        } else {
            return false; 
        }
    }

    public function update() {
        global $conn;
        $sql = "UPDATE candidato
        SET  
            nome = ?,
            cpf = ?,
            nascimento = ?
        WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $nascimentoFormatado = date('Y-m-d', strtotime($this->nascimento));



        $stmt->bind_param("sssi", $this->nome, $this->cpf,$nascimentoFormatado, $this->id);

        $results = $stmt->execute();
        return $results;
    }

    public function delete() {
        global $conn;
        $sql="DELETE FROM candidato WHERE id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $results = $stmt->execute();
        return $results;
    }

    public function getAll() {
        global $conn;
    
        $sql = "SELECT  candidato.id as id,
                        candidato.nome as nome,
                        candidato.cpf as cpf,
                        candidato.nascimento as nasc,
                        profissao.nome as nome_profissao,
                        profissao.id as id_profissao
                FROM candidato 
                INNER JOIN candidato_profissao ON candidato_profissao.id_candidato = candidato.id
                INNER JOIN profissao ON candidato_profissao.id_profissao = profissao.id";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->get_result();
    
        $data = array();
        while ($row = $results->fetch_assoc()) {
            $id_candidato = $row['id'];
            $nome_candidato = $row['nome'];
            $cpf_candidato = $row['cpf'];
            $nasc_candidato = $row['nasc'];
            $id_profissao = $row['id_profissao'];
            $nome_profissao = $row['nome_profissao'];
    
            // Adicionando candidato se ainda não existir
            if (!isset($data[$id_candidato])) {
                $data[$id_candidato] = array(
                    'id' => $id_candidato,
                    'nome' => $nome_candidato,
                    'cpf' => $cpf_candidato,
                    'nascimento' => $nasc_candidato,
                    'profissoes' => array()
                );
            }
    
            // Adicionando profissão ao candidato
            $data[$id_candidato]['profissoes'][] = array(
                'id' => $id_profissao,
                'nome' => $nome_profissao
            );
        }
    
        return $data; 
    }
    

    public function getById() {
        global $conn;
        $sql = "SELECT * FROM candidato WHERE id= ?";
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

    public function create_associacao_profis($profis){
        global $conn;
        $profis_id = $profis->getId();
        $cand_id = $this->id;

        //echo "id_candidato: ".$cand_id."\n profissão id: ".$profis_id;
        $sql="INSERT candidato_profissao (id_candidato,id_profissao) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cand_id,$profis_id);
        $stmt->execute();

        return true;
    }


    //funções auxiliares pro renzo conseguir povoar o banco de dados com informações.
    function lerArquivo($caminho_arquivo) {
        include "profissao.php";
        $candidatos = [];
        $profissoes=[];
    
        // Abrir o arquivo
        $handle = fopen($caminho_arquivo, "r");
        if ($handle) {
            // Ler linha por linha
            while (($linha = fgets($handle)) !== false) {
                // Extrair informações da linha
                $dados = explode(" ", $linha);
                $nome = $dados[0] . " " . $dados[1];
                $data_nascimento = $dados[2];
                $cpf = $dados[3];
                
                // Extrair profissões usando regex
                preg_match_all('/\[(.*?)\]/', $linha, $matches);
                $profissoes_str = $matches[1][0];
                $profissoes = explode(", ", $profissoes_str);
                
                // Criar objeto Candidato
                $candidato = new Candidato();
                $candidato->setCpf($cpf);
                $candidato->setNome($nome);
                $candidato->setNascimento($data_nascimento);
            
                echo $candidato->getNome()." ";
                $candidato->create();     //DESCOMENTAR
            
                // Adicionar Candidato à lista
                $candidatos[] = $candidato;
            
                foreach($profissoes as $profissao){
                    $profissao_obj = new Profissao();
                    $profissao_obj->setNome($profissao);
                    $profissoes[] = $profissao_obj;
            
                    echo $profissao_obj->getNome()." ";
                    $profissao_obj->create();
                    $candidato->create_associacao_profis($profissao_obj);
                }
                echo "<br>";
            }
    
            fclose($handle);
        } else {
            // Erro ao abrir o arquivo
            echo "Erro ao abrir o arquivo.";
        }
        

        return $candidatos;
    }

    function buscarConcursosCompativeis(){
        global $conn;


        $sql0="SELECT candidato.id FROM candidato WHERE cpf=?";
        $stmt = $conn->prepare($sql0);
        $stmt->bind_param("s", $this->cpf);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $this->setId($row['id']); // Extrai o ID da linha atual
            }
        } else {
            return "Nenhum resultado encontrado.";
        }


        $sql="SELECT concurso.orgao,concurso.edital,concurso.cod_concurso

        FROM candidato 
            INNER JOIN candidato_profissao ON candidato.id=candidato_profissao.id_candidato
            INNER JOIN profissao ON candidato_profissao.id_profissao=profissao.id
            INNER JOIN profissao_concurso ON profissao.id=profissao_concurso.id_profissao
            INNER JOIN concurso ON profissao_concurso.id_concurso=concurso.id
        WHERE candidato.id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $results = $stmt->get_result();


        $concursos = array();
        while ($row = $results->fetch_assoc()) {
            $concursos[] = $row;
        }
        
        return $concursos;
    }


}