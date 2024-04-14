<?php

try{

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "./../../model/candidato.php";
        require_once "./../../model/profissao.php";

        $dadosJSON = file_get_contents('php://input');
        $_POST = json_decode($dadosJSON, true);

        if ($_POST === null) {
            throw new Exception("Erro ao decodificar o JSON de entrada.");
        }
        // Verifica se as chaves necessárias estão presentes no JSON
        if (!isset($_POST["candidato"]["id"]) || !isset($_POST["profissao"]["id"])) {
            throw new Exception("Dados incompletos no JSON de entrada.");
        }

        $cand_id = $_POST["candidato"]['id'];
        $cand = new Candidato();
        $cand->setId($cand_id);


        $profis_id = $_POST["profissao"]["id"];
        $profis = new Profissao();
        $profis->setId(($profis_id));

        
        
        if ($cand->create_associacao_profis($profis)) {
            echo "Relacionamento criado com sucesso!";
        } else {
            echo "Erro ao inserir Relacionamento.";
        }
    
        }else {
        echo "Somente requisições POST são permitidas.";
    }

} catch (Exception $e) {
    // Se ocorrer um erro, exibe uma mensagem de erro
    echo "Erro na requisição POST: " . $e->getMessage();;
}
    

?>