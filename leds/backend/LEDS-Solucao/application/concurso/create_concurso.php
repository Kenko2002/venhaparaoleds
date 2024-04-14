<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/concurso.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $orgao = $_POST['orgao'];
    $edital = $_POST['edital'];
    $cod_concurso = $_POST['cod_concurso'];

    $user = new Concurso();

    $user->setOrgao($orgao);
    $user->setEdital($edital);
    $user->setCodConcurso($cod_concurso);


    if ($user->create()) {
        echo "Concurso inserido com sucesso!";
    } else {
        echo "Erro ao inserir Concurso.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>