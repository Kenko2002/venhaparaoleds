<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/candidato.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $id=$_POST["id"];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $nascimento = $_POST['nascimento'];

    $user = new Candidato();

    $user->setNome($nome);
    $user->setCpf($cpf);
    $user->setNascimento($nascimento);
    $user->setId($id);


    if ($user->update()) {
        echo "Usuário atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar usuário.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>