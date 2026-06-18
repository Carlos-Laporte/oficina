<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $erro = $_GET['erro'] ?? '';
    $veiculos = $conn->query("
        SELECT v.id, v.marca, v.modelo, v.placa, c.nome AS cliente
        FROM veiculo v
        LEFT JOIN cliente c ON v.cliente_id = c.id
        ORDER BY c.nome
    ")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nova OS - Lelicar Center Automotivo</title>
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
                        <h2>Nova Ordem de Servico</h2>
                        <p>Dashboard > Ordens de Servico > Cadastrar</p>

                        <?php if (!empty($erro)): ?>
                            <div class="alerta erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>

                        <div class="formCadastro">
                            <h3>Dados da OS</h3>
                            <form action="salvar.php" method="POST" enctype="multipart/form-data">
                                <label>Veiculo / Cliente *</label>
                                <select name="veiculo_id" required>
                                    <option value="">Selecione um veiculo</option>
                                    <?php foreach ($veiculos as $v): ?>
                                        <option value="<?= $v['id'] ?>">
                                            <?= htmlspecialchars($v['cliente'] . ' - ' . $v['marca'] . ' ' . $v['modelo'] . ' (' . $v['placa'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label>Descricao dos Servicos *</label>
                                <textarea name="descricao" rows="4" placeholder="Descreva os servicos a realizar..." required></textarea>

                                <label>Valor (R$) *</label>
                                <input type="number" name="valor" step="0.01" min="0" placeholder="0,00" required>

                                <label>Data *</label>
                                <input type="date" name="data" required>

                                <label>Comprovante / Laudo (PDF, max. 2 MB)</label>
                                <input type="file" name="comprovante" accept=".pdf">

                                <div class="formAcoes">
                                    <button type="submit"><i class="bi bi-check2-circle"></i> Abrir OS</button>
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
