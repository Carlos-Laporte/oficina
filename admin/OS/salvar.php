<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: paginaOrdemServico.php');
        exit;
    }

    $veiculo_id  = trim($_POST['veiculo_id'] ?? '');
    $descricao   = trim($_POST['descricao'] ?? '');
    $valor       = trim($_POST['valor'] ?? '');
    $data        = trim($_POST['data'] ?? '');
    $comprovante = null;
    $erro        = '';

    if (empty($veiculo_id) || empty($descricao) || empty($valor) || empty($data)) {
        $erro = 'Preencha todos os campos obrigatórios.';
    }

    if (empty($erro) && !empty($_FILES['comprovante']['name'])) {
        $arquivo = $_FILES['comprovante'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $tamanhoMax = 2 * 1024 * 1024;

        if ($extensao !== 'pdf') {
            $erro = 'Apenas arquivos PDF são permitidos.';
        } elseif ($arquivo['size'] > $tamanhoMax) {
            $erro = 'O arquivo deve ter no máximo 2 MB.';
        } else {
            $nomeArquivo = uniqid('os_') . '.pdf';
            $destino = '../../uploads/' . $nomeArquivo;

            if (!is_dir('../../uploads/')) {
                mkdir('../../uploads/', 0755, true);
            }

            if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
                $comprovante = $nomeArquivo;
            } else {
                $erro = 'Erro ao salvar o arquivo. Tente novamente.';
            }
        }
    }

    if (!empty($erro)) {
        header('Location: cadastrar.php?erro=' . urlencode($erro));
        exit;
    }

    $sql = "INSERT INTO os (veiculo_id, descricao, valor, data, comprovante, status)
            VALUES (:veiculo_id, :descricao, :valor, :data, :comprovante, 'Orçamento')";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':veiculo_id', $veiculo_id);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':valor', $valor);
    $stmt->bindValue(':data', $data);
    $stmt->bindValue(':comprovante', $comprovante);

    try {
        $stmt->execute();
        header('Location: paginaOrdemServico.php?sucesso=1');
        exit;
    } catch (PDOException $e) {
        header('Location: cadastrar.php?erro=' . urlencode('Erro ao cadastrar OS: ' . $e->getMessage()));
        exit;
    }
