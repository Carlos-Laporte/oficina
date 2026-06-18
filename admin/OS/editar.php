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

    $stmt = $conn->prepare("SELECT * FROM os WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $os = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$os) {
        header('Location: paginaOrdemServico.php');
        exit;
    }

    $veiculos = $conn->query("
        SELECT v.id, v.marca, v.modelo, v.placa, c.nome AS cliente
        FROM veiculo v
        LEFT JOIN cliente c ON v.cliente_id = c.id
        ORDER BY c.nome
    ")->fetchAll(PDO::FETCH_ASSOC);

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $veiculo_id  = trim($_POST['veiculo_id'] ?? '');
        $descricao   = trim($_POST['descricao'] ?? '');
        $valor       = trim($_POST['valor'] ?? '');
        $data        = trim($_POST['data'] ?? '');
        $status      = trim($_POST['status'] ?? '');
        $comprovante = $os['comprovante'];

        $statusValidos = ['Orçamento', 'Aprovado', 'Em andamento', 'Concluído'];

        if (empty($veiculo_id) || empty($descricao) || empty($valor) || empty($data) || !in_array($status, $statusValidos)) {
            $erro = 'Preencha todos os campos obrigatorios.';
        } else {
            if (!empty($_FILES['comprovante']['name'])) {
                $arquivo = $_FILES['comprovante'];
                $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
                $tamanhoMax = 2 * 1024 * 1024;

                if ($extensao !== 'pdf') {
                    $erro = 'Apenas arquivos PDF sao permitidos.';
                } elseif ($arquivo['size'] > $tamanhoMax) {
                    $erro = 'O arquivo deve ter no maximo 2 MB.';
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

            if (empty($erro)) {
                $sql = "UPDATE os SET veiculo_id = :veiculo_id, descricao = :descricao, valor = :valor,
                        data = :data, status = :status, comprovante = :comprovante WHERE id = :id";

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
                    $erro = 'Erro ao atualizar OS: ' . $e->getMessage();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar OS - Lelicar Center Automotivo</title>
        <link rel="stylesheet" href="../../assets/css/styleAdm.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>
    <body>
        <main class="corpo">
            <div class="corpo_main">
                <div class="corpo_lateral">
                    <div class="logoDashboard">
                        <h1>LELICAR</h1>
                        <p>CENTER AUTOMOTIVO</p>
                    </div>
                    <div class="areaDeNavegacao">
                        <div class="telas">
                            <a href="/oficina/admin/index.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                            <a href="/oficina/admin/agendamento/PaginaAgendamento.php"><i class="bi bi-postcard-fill"></i> Agendamentos</a>
                            <a href="/oficina/admin/clientes/PaginaCliente.php"><i class="bi bi-person-fill"></i> Clientes</a>
                            <a href="/oficina/admin/veiculos/paginaVeiculos.php"><i class="bi bi-car-front-fill"></i> Veiculos</a>
                            <a href="/oficina/admin/OS/paginaOrdemServico.php" class="ativo"><i class="bi bi-card-checklist"></i> Ordens de Servico</a>
                            <a href="/oficina/admin/serviços/paginaServicos.php"><i class="bi bi-tools"></i> Serviços</a>
                            <a href="#"><i class="bi bi-gear-wide-connected"></i> Pecas</a>
                            <a href="#"><i class="bi bi-currency-dollar"></i> Financeiro</a>
                            <a href="#"><i class="bi bi-graph-up"></i> Relatorios</a>
                            <a href="#"><i class="bi bi-house-gear-fill"></i> Configuracoes</a>
                            <a href="/oficina/admin/documentos/paginaDocumentos.php"><i class="bi bi-file-earmark-arrow-up"></i> Documentos</a>
                        </div>
                        <div class="sair">
                            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
                        </div>
                    </div>
                </div>
                <div class="corpoInformacao">
                    <div class="parteSuperior">
                        <h2>Ordens de Servico</h2>
                    </div>
                    <div class="parteInformacao">
                        <h2>Editar Ordem de Servico</h2>
                        <p>Dashboard > Ordens de Servico > Editar</p>

                        <?php if (!empty($erro)): ?>
                            <div class="alerta erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>

                        <div class="formCadastro">
                            <h3>OS #<?= $os['id'] ?></h3>
                            <form action="atualizar.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $os['id'] ?>">

                                <label>Veiculo / Cliente *</label>
                                <select name="veiculo_id" required>
                                    <option value="">Selecione um veiculo</option>
                                    <?php foreach ($veiculos as $v): ?>
                                        <option value="<?= $v['id'] ?>" <?= $os['veiculo_id'] == $v['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v['cliente'] . ' - ' . $v['marca'] . ' ' . $v['modelo'] . ' (' . $v['placa'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label>Descricao dos Servicos *</label>
                                <textarea name="descricao" rows="4" required><?= htmlspecialchars($os['descricao']) ?></textarea>

                                <label>Valor (R$) *</label>
                                <input type="number" name="valor" step="0.01" min="0" value="<?= htmlspecialchars($os['valor']) ?>" required>

                                <label>Data *</label>
                                <input type="date" name="data" value="<?= htmlspecialchars($os['data']) ?>" required>

                                <label>Status *</label>
                                <select name="status" required>
                                    <?php foreach (['Orçamento', 'Aprovado', 'Em andamento', 'Concluído'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $os['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <label>Novo Comprovante / Laudo (PDF, max. 2 MB)</label>
                                <?php if (!empty($os['comprovante'])): ?>
                                    <p class="arquivoAtual">Atual: <a href="../../uploads/<?= urlencode($os['comprovante']) ?>" target="_blank">Ver PDF atual</a></p>
                                <?php endif; ?>
                                <input type="file" name="comprovante" accept=".pdf">

                                <div class="formAcoes">
                                    <button type="submit"><i class="bi bi-check2-circle"></i> Salvar Alteracoes</button>
                                    <a href="paginaOrdemServico.php" class="btnCancelar"><i class="bi bi-x-circle"></i> Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
