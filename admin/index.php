<?php
    session_start();

    if (!isset($_SESSION['adm_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once("../conexao.php");

    $totalClientes = (int) $conn->query("SELECT COUNT(*) FROM cliente")->fetchColumn();
    $totalVeiculos = (int) $conn->query("SELECT COUNT(*) FROM veiculo")->fetchColumn();
    $totalAgendamentos = (int) $conn->query("SELECT COUNT(*) FROM agendamento")->fetchColumn();
    $agendamentosHoje = (int) $conn->query("SELECT COUNT(*) FROM agendamento WHERE data = CURDATE()")->fetchColumn();
    $totalOrdens = (int) $conn->query("SELECT COUNT(*) FROM os")->fetchColumn();
    $faturamento = (float) $conn->query("SELECT COALESCE(SUM(valor), 0) FROM os")->fetchColumn();
    $statusOrdens = [
        'Orçamento' => 0,
        'Aprovado' => 0,
        'Em andamento' => 0,
        'Concluído' => 0,
    ];

    $stmtStatus = $conn->query("SELECT status, COUNT(*) AS total FROM os GROUP BY status");
    foreach ($stmtStatus->fetchAll(PDO::FETCH_ASSOC) as $status) {
        if (array_key_exists($status['status'], $statusOrdens)) {
            $statusOrdens[$status['status']] = (int) $status['total'];
        }
    }

    $proximosAgendamentos = $conn->query("
        SELECT id, nome, telefone, veiculo, servico, data, horario
        FROM agendamento
        WHERE data >= CURDATE()
        ORDER BY data ASC, horario ASC
        LIMIT 6
    ")->fetchAll(PDO::FETCH_ASSOC);

    $ultimasOrdens = $conn->query("
        SELECT os.id, os.descricao, os.valor, os.data, os.status,
               v.marca, v.modelo,
               c.nome AS cliente
        FROM os
        LEFT JOIN veiculo v ON os.veiculo_id = v.id
        LEFT JOIN cliente c ON v.cliente_id = c.id
        ORDER BY os.id DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Lelicar Center Automotivo</title>
        <link rel="stylesheet" href="../assets/css/styleAdm.css">
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
                            <a href="index.php" class="ativo"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                            <a href="agendamento/PaginaAgendamento.php"><i class="bi bi-postcard-fill"></i> Agendamentos</a>
                            <a href="clientes/PaginaCliente.php"><i class="bi bi-person-fill"></i> Clientes</a>
                            <a href="veiculos/paginaVeiculos.php"><i class="bi bi-car-front-fill"></i> Veículos</a>  
                            <a href="OS/paginaOrdemServico.php"><i class="bi bi-card-checklist"></i> Ordens de Serviço</a>
                            <a href="#"><i class="bi bi-tools"></i> Serviços</a>
                            <a href="#"><i class="bi bi-gear-wide-connected"></i> Peças</a>
                            <a href="#"><i class="bi bi-currency-dollar"></i> Financeiro</a>
                            <a href="#"><i class="bi bi-graph-up"></i> Relatórios</a>
                            <a href="#"><i class="bi bi-house-gear-fill"></i> Configurações</a>
                            <a href="/oficina/admin/documentos/paginaDocumentos.php"><i class="bi bi-file-earmark-arrow-up"></i> Documentos</a>
                        </div>
                        <div class="sair">
                            <a href="../admin/logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a>
                        </div>
                    </div>
                </div>
                <div class="corpoInformacao">
                    <div class="parteSuperior">
                        <h2>Painel Administrativo</h2>
                    </div>
                    <div class="parteInformacao">
                        <h2>Dashboard</h2>
                        <p>Visão geral da oficina mecânica.</p>

                        <div class="dashboardGrid">
                            <a class="dashboardCard" href="agendamento/PaginaAgendamento.php">
                                <span><i class="bi bi-calendar-check"></i></span>
                                <div>
                                    <p>Agendamentos</p>
                                    <strong><?= $totalAgendamentos ?></strong>
                                    <small>Atalho para consultar e cadastrar visitas</small>
                                </div>
                                <i class="bi bi-arrow-right-short atalhoIcone"></i>
                            </a>
                            <a class="dashboardCard" href="clientes/PaginaCliente.php">
                                <span><i class="bi bi-people-fill"></i></span>
                                <div>
                                    <p>Clientes</p>
                                    <strong><?= $totalClientes ?></strong>
                                    <small>Atalho para gerenciar dados dos clientes</small>
                                </div>
                                <i class="bi bi-arrow-right-short atalhoIcone"></i>
                            </a>
                            <a class="dashboardCard" href="veiculos/paginaVeiculos.php">
                                <span><i class="bi bi-car-front-fill"></i></span>
                                <div>
                                    <p>Veículos</p>
                                    <strong><?= $totalVeiculos ?></strong>
                                    <small>Atalho para acessar os veículos cadastrados</small>
                                </div>
                                <i class="bi bi-arrow-right-short atalhoIcone"></i>
                            </a>
                            <a class="dashboardCard" href="OS/paginaOrdemServico.php">
                                <span><i class="bi bi-card-checklist"></i></span>
                                <div>
                                    <p>Ordens de serviço</p>
                                    <strong><?= $totalOrdens ?></strong>
                                    <small>Atalho para acompanhar todas as OS</small>
                                </div>
                                <i class="bi bi-arrow-right-short atalhoIcone"></i>
                            </a>
                        </div>

                        <div class="dashboardStatus">
                            <a class="statusCard status-orcamento" href="OS/paginaOrdemServico.php">
                                <span>Orçamento</span>
                                <strong><?= $statusOrdens['Orçamento'] ?></strong>
                                <p>OS aguardando aprovação</p>
                            </a>
                            <a class="statusCard status-aprovado" href="OS/paginaOrdemServico.php">
                                <span>Aprovada</span>
                                <strong><?= $statusOrdens['Aprovado'] ?></strong>
                                <p>OS liberadas pelo cliente</p>
                            </a>
                            <a class="statusCard status-andamento" href="OS/paginaOrdemServico.php">
                                <span>Em andamento</span>
                                <strong><?= $statusOrdens['Em andamento'] ?></strong>
                                <p>OS em execução na oficina</p>
                            </a>
                            <a class="statusCard status-concluido" href="OS/paginaOrdemServico.php">
                                <span>Concluída</span>
                                <strong><?= $statusOrdens['Concluído'] ?></strong>
                                <p>OS finalizadas</p>
                            </a>
                        </div>

                        <div class="dashboardResumo">
                            <div class="resumoItem">
                                <span>Hoje</span>
                                <strong><?= $agendamentosHoje ?></strong>
                                <p>agendamento(s)</p>
                            </div>
                            <div class="resumoItem">
                                <span>Total de OS</span>
                                <strong><?= $totalOrdens ?></strong>
                                <p>ordem(ns) de serviço</p>
                            </div>
                            <div class="resumoItem">
                                <span>Total em OS</span>
                                <strong>R$ <?= number_format($faturamento, 2, ',', '.') ?></strong>
                                <p>valor registrado</p>
                            </div>
                        </div>

                        <div class="dashboardColunas">
                            <section class="painelDashboard">
                                <div class="painelTopo">
                                    <h3>Próximos agendamentos</h3>
                                    <a href="agendamento/PaginaAgendamento.php">Ver todos</a>
                                </div>
                                <div class="corpoTabela">
                                    <table class="informacaoTabela">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Veículo</th>
                                                <th>Serviço</th>
                                                <th>Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($proximosAgendamentos)): ?>
                                                <tr>
                                                    <td colspan="4">Nenhum agendamento futuro encontrado.</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php foreach ($proximosAgendamentos as $agendamento): ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($agendamento['nome']) ?><br>
                                                        <small><?= htmlspecialchars($agendamento['telefone']) ?></small>
                                                    </td>
                                                    <td><?= htmlspecialchars($agendamento['veiculo']) ?></td>
                                                    <td><?= htmlspecialchars($agendamento['servico']) ?></td>
                                                    <td>
                                                        <?= date('d/m/Y', strtotime($agendamento['data'])) ?><br>
                                                        <small><?= htmlspecialchars(substr($agendamento['horario'], 0, 5)) ?></small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>

                            <section class="painelDashboard">
                                <div class="painelTopo">
                                    <h3>Últimas OS</h3>
                                    <a href="OS/paginaOrdemServico.php">Ver todas</a>
                                </div>
                                <div class="listaDashboard">
                                    <?php if (empty($ultimasOrdens)): ?>
                                        <p class="vazioDashboard">Nenhuma ordem de serviço cadastrada.</p>
                                    <?php endif; ?>
                                    <?php foreach ($ultimasOrdens as $ordem): ?>
                                        <a href="OS/editar.php?id=<?= $ordem['id'] ?>" class="itemDashboard">
                                            <div>
                                                <strong><?= htmlspecialchars($ordem['cliente'] ?? 'Cliente não informado') ?></strong>
                                                <p><?= htmlspecialchars(trim(($ordem['marca'] ?? '') . ' ' . ($ordem['modelo'] ?? '')) ?: 'Veículo não informado') ?></p>
                                            </div>
                                            <span>
                                                R$ <?= number_format((float) $ordem['valor'], 2, ',', '.') ?><br>
                                                <small><?= htmlspecialchars($ordem['status']) ?></small>
                                            </span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
