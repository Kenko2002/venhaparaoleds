<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/candidato.php";


    $user = new Candidato();

    $user->lerArquivo("./../../../candidatos.txt");


} else {
    echo "Somente requisições POST são permitidas.";
}

?>