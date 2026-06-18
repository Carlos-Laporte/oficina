<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $id = intval($_GET['id'] ?? 0);
    if (!$id) {
        header('Location: PaginaCliente.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM cliente WHERE id = :id");
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        header('Location: PaginaCliente.php');
        exit;
    }

    $erro = $_GET['erro'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Cliente - Lelicar Center Automotivo</title>
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
                            <a href="/oficina/admin/clientes/PaginaCliente.php" class="ativo"><i class="bi bi-person-fill"></i> Clientes</a>
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
                        <h2>Clientes</h2>
                    </div>
                    <div class="parteInformacao">
                        <h2>Editar Cliente</h2>
                        <p>Dashboard > Clientes > Editar</p>

                        <?php if (!empty($erro)): ?>
                            <div class="alerta erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>

                        <div class="formCadastro">
                            <h3>Cliente #<?= $cliente['id'] ?></h3>
                            <form action="atualizar.php" method="POST">
                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

                                <label>Nome *</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

                                <label>CPF *</label>
                                <input type="text" name="cpf" value="<?= htmlspecialchars($cliente['cpf']) ?>" required>

                                <label>Telefone</label>
                                <input type="text" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>">

                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>">

                                <div class="formAcoes">
                                    <button type="submit"><i class="bi bi-check2-circle"></i> Salvar Alteracoes</button>
                                    <a href="PaginaCliente.php" class="btnCancelar"><i class="bi bi-x-circle"></i> Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
