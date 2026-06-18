<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header('Location: PaginaCliente.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM cliente WHERE id = :id");
$stmt->bindValue(':id', $id);
try {
    $stmt->execute();
} catch (PDOException $e) {
    // opcional: log ou redirecionar com erro
}

header('Location: PaginaCliente.php');
exit;
?>
<?php
// admin/modulo/excluir.php

echo '<h1>Excluir registro</h1>';
