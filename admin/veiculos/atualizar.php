<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: paginaVeiculos.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$cliente_id = intval($_POST['cliente_id'] ?? 0);
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$placa = trim($_POST['placa'] ?? '');
$ano = trim($_POST['ano'] ?? '');
$erro = '';

if (!$id || !$cliente_id || empty($marca) || empty($modelo) || empty($placa)) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Preencha todos os campos obrigatórios.'));
    exit;
}

$sql = "UPDATE veiculo SET
            cliente_id = :cliente_id,
            marca = :marca,
            modelo = :modelo,
            placa = :placa,
            ano = :ano
        WHERE id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':cliente_id', $cliente_id);
$stmt->bindValue(':marca', $marca);
$stmt->bindValue(':modelo', $modelo);
$stmt->bindValue(':placa', $placa);
$stmt->bindValue(':ano', $ano);
$stmt->bindValue(':id', $id);

try {
    $stmt->execute();
    header('Location: paginaVeiculos.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Erro ao atualizar veículo: ' . $e->getMessage()));
    exit;
}
