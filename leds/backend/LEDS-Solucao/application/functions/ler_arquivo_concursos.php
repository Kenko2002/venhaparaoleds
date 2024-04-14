<?php



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./../../model/concurso.php";


    $user = new Concurso();

    $user->lerArquivo("./../../../concursos.txt");


} else {
    echo "Somente requisições POST são permitidas.";
}

?>