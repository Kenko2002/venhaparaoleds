<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "./../../model/concurso.php";
    
    $user = new Concurso();
    $results=$user->getAll();
    echo json_encode($results);
    
} else {
    echo "Somente requisições GET são permitidas.";
}

?>