<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/profissao.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $id = $_POST['id'];
    $nome = $_POST['nome'];

    $user = new Profissao();

    $user->setId($id);
    $user->setNome($nome);


    if ($user->update()) {
        echo "Profissao atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar Profissao.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>