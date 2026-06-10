<?php
    require_once("../conexao.php");

    if(isset($_SESSION['adm_id'])){

        $stmt = $conn->query("SELECT * FROM cliente");

        $resultado = $stmt->fetchALL();

    } else{
        header('Location: ../admin/login.php');
        exit();
    }
?>