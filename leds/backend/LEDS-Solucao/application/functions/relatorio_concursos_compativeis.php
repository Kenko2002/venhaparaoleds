<?php
try{

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        require_once "./../../model/candidato.php";

        if ($_POST === null) {
            throw new Exception("Erro ao decodificar o JSON de entrada.");
        }
        // Verifica se as chaves necessárias estão presentes no JSON
        if (!isset($_GET["cpf"])) {
            throw new Exception("Dados incompletos no JSON de entrada.");
        }
        
        $user = new Candidato();
        $user->setCpf($_GET["cpf"]);

        echo json_encode($user->buscarConcursosCompativeis(),JSON_UNESCAPED_SLASHES);


    } else {
        echo "Somente requisições GET são permitidas.";
    }

}catch (Exception $e) {
    // Se ocorrer um erro, exibe uma mensagem de erro
    echo "Erro na requisição GET: " . $e->getMessage();;
}

?>
