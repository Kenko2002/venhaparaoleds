<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "./../../model/profissao.php";


    $user = new Profissao();
    $results=$user->getAll();
    echo json_encode($results);
    
} else {
    echo "Somente requisições GET são permitidas.";
}

?>