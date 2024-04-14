<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/candidato.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $nascimento = $_POST['nascimento'];

    $user = new Candidato();

    $user->setNome($nome);
    $user->setCpf($cpf);
    $user->setNascimento($nascimento);


    if ($user->create()) {
        echo "Usuário inserido com sucesso!";
    } else {
        echo "Erro ao inserir usuário.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>