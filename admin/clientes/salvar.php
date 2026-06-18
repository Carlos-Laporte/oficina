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

$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim($_POST['email'] ?? '');
$adm_id = $_SESSION['adm_id'];
$erro = '';

if (empty($nome) || empty($cpf)) {
    $erro = 'Preencha todos os campos obrigatórios.';
}

if (!empty($erro)) {
    header('Location: cadastrar.php?erro=' . urlencode($erro));
    exit;
}

$sql = "INSERT INTO cliente (nome, cpf, telefone, email, adm_id)
        VALUES (:nome, :cpf, :telefone, :email, :adm_id)";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':cpf', $cpf);
$stmt->bindValue(':telefone', $telefone);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':adm_id', $adm_id);

try {
    $stmt->execute();
    header('Location: PaginaCliente.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    header('Location: cadastrar.php?erro=' . urlencode('Erro ao cadastrar cliente: ' . $e->getMessage()));
    exit;
}
