<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: PaginaAgendamento.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$veiculo = trim($_POST['veiculo'] ?? '');
$ano = trim($_POST['ano'] ?? '');
$servico = trim($_POST['servico'] ?? '');
$data = trim($_POST['data'] ?? '');
$horario = trim($_POST['horario'] ?? '');
$comentario = trim($_POST['comentario'] ?? '');
$erro = '';

if (!$id || empty($nome) || empty($telefone) || empty($veiculo) || empty($servico) || empty($data) || empty($horario)) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Preencha todos os campos obrigatórios.'));
    exit;
}

$sql = "UPDATE agendamento SET
            nome = :nome,
            email = :email,
            telefone = :telefone,
            veiculo = :veiculo,
            ano = :ano,
            servico = :servico,
            data = :data,
            horario = :horario,
            comentario = :comentario
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':telefone', $telefone);
$stmt->bindValue(':veiculo', $veiculo);
$stmt->bindValue(':ano', $ano);
$stmt->bindValue(':servico', $servico);
$stmt->bindValue(':data', $data);
$stmt->bindValue(':horario', $horario);
$stmt->bindValue(':comentario', $comentario);
$stmt->bindValue(':id', $id);

try {
    $stmt->execute();
    header('Location: PaginaAgendamento.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Erro ao atualizar agendamento: ' . $e->getMessage()));
    exit;
}
