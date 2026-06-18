<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        header('Location: paginaOrdemServico.php');
        exit;
    }

    // Buscar o comprovante para deletar o arquivo
    $stmt = $conn->prepare("SELECT comprovante FROM os WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $os = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($os) {
        // Remove o arquivo PDF se existir
        if (!empty($os['comprovante'])) {
            $arquivo = '../../uploads/' . $os['comprovante'];
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
        }

        // Remove o registro do banco
        $del = $conn->prepare("DELETE FROM os WHERE id = :id");
        $del->bindValue(':id', $id);
        $del->execute();
    }

    header('Location: paginaOrdemServico.php');
    exit;
