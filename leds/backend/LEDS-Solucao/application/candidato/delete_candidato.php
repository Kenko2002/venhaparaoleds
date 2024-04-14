<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/candidato.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $id = $_POST['id'];

    $user = new Candidato();
    $user->setId($id);

    if ($user->delete()) {
        echo "Usuário deletado com sucesso!";
    } else {
        echo "Erro ao deletar usuário.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>