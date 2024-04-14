<?php

include_once "./../../conexao.php";

class Concurso {
    private $id;
    private $orgao;
    private $edital;
    private $cod_concurso;

    // Construtor
    public function __construct() {
        $this->id = 0;
        $this->orgao = "null";
        $this->edital = "null";
        $this->cod_concurso = "0000/00/00";
    }

    

    // Métodos Getters
    public function getId() {
        return $this->id;
    }

    public function getOrgao() {
        return $this->orgao;
    }

    public function getEdital() {
        return $this->edital;
    }

    public function getCodConcurso() {
        return $this->cod_concurso;
    }

    // Métodos Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setOrgao($orgao) {
        $this->orgao = $orgao;
    }

    public function setEdital($edital) {
        $this->edital = $edital;
    }

    public function setCodConcurso($cod_concurso) {
        $this->cod_concurso = $cod_concurso;
    }


    //Funções de Repository para manipulação de dados.
    
    public function create() {
        global $conn;
        if(empty($this->orgao) || empty($this->cod_concurso) || empty($this->edital)) {
            return false; // Se algum dado estiver faltando, retorna false
        }
        $sql = "INSERT INTO concurso (orgao, cod_concurso, edital) VALUES (?, ?, ?)";
    
        $stmt = $conn->prepare($sql);


        $stmt->bind_param("sss", $this->orgao, $this->cod_concurso,$this->edital);
    
        if($stmt->execute()) {
            $this->setId( mysqli_insert_id($conn) );
            return true; 
        } else {
            return false; 
        }
    }

    public function update() {
        global $conn;
        $sql = "UPDATE concurso
        SET  
            orgao = ?,
            cod_concurso = ?,
            edital = ?
        WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sssi", $this->orgao, $this->cod_concurso,$this->edital, $this->id);

        $results = $stmt->execute();

        
        return $results;
    }

    public function delete() {
        global $conn;
        $sql="DELETE FROM concurso WHERE id= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $results = $stmt->execute();
        return $results;
    }

    /*
    public function getAll() {
        global $conn;
        $sql = "SELECT * FROM concurso";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->get_result();
        
        $data = array();
        while ($row = $results->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data; 
    }
    */
    public function getAll() {
        global $conn;
    
        $sql = "SELECT  concurso.id as id,
                        concurso.orgao as orgao,
                        concurso.edital as edital,
                        concurso.cod_concurso as cod_concurso,
                        profissao.nome as nome_profissao,
                        profissao.id as id_profissao
                FROM concurso
                INNER JOIN profissao_concurso ON profissao_concurso.id_concurso = concurso.id
                INNER JOIN profissao ON profissao_concurso.id_profissao = profissao.id";
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->get_result();
    
        $data = array();
        while ($row = $results->fetch_assoc()) {
            $id_concurso = $row['id'];
            $orgao_concurso = $row['orgao'];
            $edital_concurso = $row['edital'];
            $cod_concurso = $row['cod_concurso'];
            $id_profissao = $row['id_profissao'];
            $nome_profissao = $row['nome_profissao'];
    
            // Adicionando concurso se ainda não existir
            if (!isset($data[$id_concurso])) {
                $data[$id_concurso] = array(
                    'id' => $id_concurso,
                    'orgao' => $orgao_concurso,
                    'edital' => $edital_concurso,
                    'cod' => $cod_concurso,
                    'profissoes' => array()
                );
            }
    
            // Adicionando profissão ao concurso
            $data[$id_concurso]['profissoes'][] = array(
                'id' => $id_profissao,
                'nome' => $nome_profissao
            );
        }
    
        return $data; 
    }


    public function getById() {
        global $conn;
        $sql = "SELECT * FROM concurso WHERE id= ?";
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
        $conc_id = $this->id;

        $sql="INSERT profissao_concurso (id_concurso,id_profissao) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $conc_id,$profis_id);
        $stmt->execute();

        return true;
    }

    function lerArquivo($caminho_arquivo) {
        include "profissao.php";
        $concursos = [];
    
        // Abrir o arquivo
        $handle = fopen($caminho_arquivo, "r");
        if ($handle) {
            // Ler linha por linha
            while (($linha = fgets($handle)) !== false) {
                // Extrair informações da linha
                $dados = explode(" ", $linha);
                $orgao = $dados[0];
                $edital = $dados[1];
                $cod_concurso = $dados[2];
                
                // Extrair profissões usando regex
                preg_match_all('/\[(.*?)\]/', $linha, $matches);
                $profissoes = [];
                foreach ($matches[1] as $match) {
                    $profissoes = array_merge($profissoes, explode(", ", $match));
                }
                
                // Criar objeto concurso
                $concurso = new Concurso();
                $concurso->setOrgao($orgao);
                $concurso->setEdital($edital);
                $concurso->setCodConcurso($cod_concurso);
            
                echo $concurso->getOrgao()." ";
                $concurso->create();     //DESCOMENTAR
            
                // Adicionar concurso à lista
                $concursos[] = $concurso;
            
                foreach($profissoes as $profissao){
                    $profissao_obj = new Profissao();
                    $profissao_obj->setNome($profissao);
            
                    echo $profissao_obj->getNome()." ";
                    $profissao_obj->create();
                    $concurso->create_associacao_profis($profissao_obj);
                }
                echo "<br>";
            }
    
            fclose($handle);
        } else {
            // Erro ao abrir o arquivo
            echo "Erro ao abrir o arquivo.";
        }
        
    
        return $concursos;
    }
    
    function buscarCandidatosCompativeis(){
        global $conn;

        $sql0="SELECT concurso.id FROM concurso WHERE cod_concurso=?";
        $stmt = $conn->prepare($sql0);
        $stmt->bind_param("s", $this->cod_concurso);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $this->setId($row['id']); // Extrai o ID da linha atual
            }
        } else {
            return "Nenhum resultado encontrado.";
        }


        $sql="SELECT candidato.nome as cand_nome,candidato.cpf,candidato.nascimento

        FROM candidato 
            INNER JOIN candidato_profissao ON candidato.id=candidato_profissao.id_candidato
            INNER JOIN profissao ON candidato_profissao.id_profissao=profissao.id
            INNER JOIN profissao_concurso ON profissao.id=profissao_concurso.id_profissao
            INNER JOIN concurso ON profissao_concurso.id_concurso=concurso.id
        WHERE concurso.id=?";

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