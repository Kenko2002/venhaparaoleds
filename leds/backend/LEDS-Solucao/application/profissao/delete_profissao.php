<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/profissao.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $id = $_POST['id'];

    $user = new Profissao();
    $user->setId($id);

    if ($user->delete()) {
        echo "Profissao deletado com sucesso!";
    } else {
        echo "Erro ao deletar Profissao.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>