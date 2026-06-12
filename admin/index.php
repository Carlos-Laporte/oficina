<?php
    session_start();


    if (!isset($_SESSION['adm_id'])) {
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
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
                            <a href="index.php"><i class="bi bi-house-door-fill"></i> Dashboard</a>
                            <a href="agendamento/PaginaAgendamento.php"><i class="bi bi-postcard-fill"></i> Agendamentos</a>
                            <a href="clientes/PaginaCliente.php"><i class="bi bi-person-fill"></i> Clientes</a>
                            <a href="veiculos/paginaVeiculos.php"><i class="bi bi-car-front-fill"></i> Veículos</a>  
                            <a href="OS/paginaOrdemServico.php"><i class="bi bi-card-checklist"></i> Ordens de Serviço</a>
                            <a href="servicos/paginaServicos.php"><i class="bi bi-tools"></i> Serviços</a>
                            <a href="pecas/paginaPecas.php"><i class="bi bi-gear-wide-connected"></i> Peças</a>
                            <a href="financeiro/paginaFinanceiro.php"><i class="bi bi-currency-dollar"></i> Financeiro</a>
                            <a href="relatorios/paginaRelatorios.php"><i class="bi bi-graph-up"></i> Relatórios</a>
                            <a href="configuracao/paginaConfiguracao.php"><i class="bi bi-house-gear-fill"></i> Configurações</a>
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
                        <p>Bem-vindo ao sistema da oficina mecânica.</p>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>