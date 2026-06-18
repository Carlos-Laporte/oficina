<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        header('Location: PaginaAgendamento.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM agendamento WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $ag = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ag) {
        header('Location: PaginaAgendamento.php');
        exit;
    }

    $erro = $_GET['erro'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Agendamento - Lelicar Center Automotivo</title>
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
                            <a href="/oficina/admin/agendamento/PaginaAgendamento.php" class="ativo"><i class="bi bi-postcard-fill"></i> Agendamentos</a>
                            <a href="/oficina/admin/clientes/PaginaCliente.php"><i class="bi bi-person-fill"></i> Clientes</a>
                            <a href="/oficina/admin/veiculos/paginaVeiculos.php"><i class="bi bi-car-front-fill"></i> Veiculos</a>
                            <a href="/oficina/admin/OS/paginaOrdemServico.php"><i class="bi bi-card-checklist"></i> Ordens de Servico</a>
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
                        <h2>Agendamentos</h2>
                    </div>
                    <div class="parteInformacao">
                        <h2>Editar Agendamento</h2>
                        <p>Dashboard > Agendamentos > Editar</p>

                        <?php if (!empty($erro)): ?>
                            <div class="alerta erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>

                        <div class="formCadastro">
                            <h3>Agendamento #<?= $ag['id'] ?></h3>
                            <form action="atualizar.php" method="POST">
                                <input type="hidden" name="id" value="<?= $ag['id'] ?>">

                                <label>Nome *</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($ag['nome']) ?>" required>

                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($ag['email']) ?>">

                                <label>Telefone *</label>
                                <input type="text" name="telefone" value="<?= htmlspecialchars($ag['telefone']) ?>" required>

                                <label>Veiculo *</label>
                                <input type="text" name="veiculo" value="<?= htmlspecialchars($ag['veiculo']) ?>" required>

                                <label>Ano</label>
                                <input type="number" name="ano" value="<?= htmlspecialchars($ag['ano']) ?>">

                                <label>Serviço *</label>
                                <select name="servico" required>
                                    <?php foreach (['Manutenção', 'Reparo', 'Diagnóstico'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $ag['servico'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <label>Data *</label>
                                <input type="date" name="data" value="<?= htmlspecialchars($ag['data']) ?>" required>

                                <label>Horario *</label>
                                <input type="time" name="horario" value="<?= htmlspecialchars($ag['horario']) ?>" required>

                                <label>Comentario</label>
                                <textarea name="comentario" rows="3"><?= htmlspecialchars($ag['comentario']) ?></textarea>

                                <div class="formAcoes">
                                    <button type="submit"><i class="bi bi-check2-circle"></i> Salvar Alteracoes</button>
                                    <a href="PaginaAgendamento.php" class="btnCancelar"><i class="bi bi-x-circle"></i> Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
