<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/concurso.php";

    $dadosJSON = file_get_contents('php://input');
    $_POST = json_decode($dadosJSON, true);

    $orgao = $_POST['orgao'];
    $edital = $_POST['edital'];
    $cod_concurso = $_POST['cod_concurso'];
    $id=$_POST["id"];

    $user = new Concurso();

    $user->setOrgao($orgao);
    $user->setEdital($edital);
    $user->setCodConcurso($cod_concurso);
    $user->setId($id);


    if ($user->update()) {
        echo "Concurso atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar concurso.";
    }
} else {
    echo "Somente requisições POST são permitidas.";
}

?>