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

    $id = intval($_POST['id'] ?? 0);
    $veiculo_id = trim($_POST['veiculo_id'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data = trim($_POST['data'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $erro = '';

    $statusValidos = ['Orçamento', 'Aprovado', 'Em andamento', 'Concluído'];

    if (!$id || empty($veiculo_id) || empty($descricao) || empty($valor) || empty($data) || !in_array($status, $statusValidos)) {
        header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Preencha todos os campos obrigatórios.'));
        exit;
    }

    $stmt = $conn->prepare("SELECT comprovante FROM os WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $os = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$os) {
        header('Location: paginaOrdemServico.php');
        exit;
    }

    $comprovante = $os['comprovante'];

    if (!empty($_FILES['comprovante']['name'])) {
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

            if (!is_dir('../../uploads/')) mkdir('../../uploads/', 0755, true);

            if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
                if (!empty($os['comprovante']) && file_exists('../../uploads/' . $os['comprovante'])) {
                    unlink('../../uploads/' . $os['comprovante']);
                }
                $comprovante = $nomeArquivo;
            } else {
                $erro = 'Erro ao salvar o arquivo.';
            }
        }
    }

    if (!empty($erro)) {
        header('Location: editar.php?id=' . $id . '&erro=' . urlencode($erro));
        exit;
    }

    $sql = "UPDATE os SET
                veiculo_id = :veiculo_id,
                descricao = :descricao,
                valor = :valor,
                data = :data,
                status = :status,
                comprovante = :comprovante
            WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':veiculo_id', $veiculo_id);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':valor', $valor);
    $stmt->bindValue(':data', $data);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':comprovante', $comprovante);
    $stmt->bindValue(':id', $id);

    try {
        $stmt->execute();
        header('Location: paginaOrdemServico.php?sucesso=1');
        exit;
    } catch (PDOException $e) {
        header('Location: editar.php?id=' . $id . '&erro=' . urlencode('Erro ao atualizar: ' . $e->getMessage()));
        exit;
    }
