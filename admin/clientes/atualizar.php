<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: PaginaCliente.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$erro = '';

if (!$id || empty($nome) || empty($cpf)) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Preencha todos os campos obrigatórios.'));
    exit;
}

$sql = "UPDATE cliente SET
            nome = :nome,
            cpf = :cpf,
            telefone = :telefone,
            email = :email
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':cpf', $cpf);
$stmt->bindValue(':telefone', $telefone);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':id', $id);

try {
    $stmt->execute();
    header('Location: PaginaCliente.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Erro ao atualizar cliente: ' . $e->getMessage()));
    exit;
}
