<?php

try{

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        require_once "./../../model/concurso.php";

        if ($_POST === null) {
            throw new Exception("Erro ao decodificar o JSON de entrada.");
        }
        // Verifica se as chaves necessárias estão presentes no JSON
        if (!isset($_GET["cod_concurso"])) {
            throw new Exception("Dados incompletos no JSON de entrada.");
        }

        $user = new Concurso();
        $user->setCodConcurso($_GET["cod_concurso"]);

        echo json_encode($user->buscarCandidatosCompativeis(),JSON_UNESCAPED_SLASHES);


    } else {
        echo "Somente requisições GET são permitidas.";
    } 

}catch (Exception $e) {
    // Se ocorrer um erro, exibe uma mensagem de erro
    echo "Erro na requisição GET: " . $e->getMessage();;
}

?>
