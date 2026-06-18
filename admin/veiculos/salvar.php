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

$cliente_id = intval($_POST['cliente_id'] ?? 0);
$marca = trim($_POST['marca'] ?? '');
$modelo = trim($_POST['modelo'] ?? '');
$placa = trim($_POST['placa'] ?? '');
$ano = trim($_POST['ano'] ?? '');
$erro = '';

if (!$cliente_id || empty($marca) || empty($modelo) || empty($placa)) {
    $erro = 'Preencha todos os campos obrigatórios.';
}

if (!empty($erro)) {
    header('Location: cadastrar.php?erro=' . urlencode($erro));
    exit;
}

$sql = "INSERT INTO veiculo (cliente_id, marca, modelo, placa, ano)
        VALUES (:cliente_id, :marca, :modelo, :placa, :ano)";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':cliente_id', $cliente_id);
$stmt->bindValue(':marca', $marca);
$stmt->bindValue(':modelo', $modelo);
$stmt->bindValue(':placa', $placa);
$stmt->bindValue(':ano', $ano);

try {
    $stmt->execute();
    header('Location: paginaVeiculos.php?sucesso=1');
    exit;
} catch (PDOException $e) {
    header('Location: cadastrar.php?erro=' . urlencode('Erro ao cadastrar veículo: ' . $e->getMessage()));
    exit;
}
