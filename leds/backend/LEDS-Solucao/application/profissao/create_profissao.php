<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/profissao.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $nome = $_POST['nome'];

    $user = new Profissao();

    $user->setNome($nome);


    if ($user->create()) {
        echo "Profissao inserido com sucesso!";
    } else {
        echo "Erro ao inserir Profissao.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>