<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
    header('Location: ../../login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    header('Location: PaginaAgendamento.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM agendamento WHERE id = :id");
$stmt->bindValue(':id', $id);
try {
    $stmt->execute();
} catch (PDOException $e) {
    // opcional: log ou redirecionar com erro
}

header('Location: PaginaAgendamento.php');
exit;
?>
