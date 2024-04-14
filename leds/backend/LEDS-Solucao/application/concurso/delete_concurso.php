<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/concurso.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $id = $_POST['id'];

    $user = new Concurso();
    $user->setId($id);

    if ($user->delete()) {
        echo "Concurso deletado com sucesso!";
    } else {
        echo "Erro ao deletar Concurso.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>