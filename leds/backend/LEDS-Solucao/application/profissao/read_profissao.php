<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "./../../model/profissao.php";

    $id=$_GET["id"];
    
    $user = new Profissao();
    $user->setId($id);

    $results=$user->getById();
    echo json_encode($results);
    
} else {
    echo "Somente requisições GET são permitidas.";
}

?>