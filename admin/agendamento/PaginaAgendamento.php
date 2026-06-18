<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agendamentos — Lelicar Center Automotivo</title>
        <link rel="stylesheet" href="../../assets/css/styleAdm.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
                            <a href="/oficina/admin/veiculos/paginaVeiculos.php"><i class="bi bi-car-front-fill"></i> Veículos</a>
                            <a href="/oficina/admin/OS/paginaOrdemServico.php"><i class="bi bi-card-checklist"></i> Ordens de Serviço</a>
                            <a href="#"><i class="bi bi-tools"></i> Serviços</a>
                            <a href="#"><i class="bi bi-gear-wide-connected"></i> Peças</a>
                            <a href="#"><i class="bi bi-currency-dollar"></i> Financeiro</a>
                            <a href="#"><i class="bi bi-graph-up"></i> Relatórios</a>
                            <a href="#"><i class="bi bi-house-gear-fill"></i> Configurações</a>
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
                        <h2>Agendamentos</h2>
                        <p>Dashboard > Agendamentos</p>
                        <div class="conjunto">
                            <div class="corpoDivVeiculo">
                                <div class="corpoTabela">
                                    <?php require_once("listar.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#tabela').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' }
                });
            });
        </script>
    </body>
</html>
