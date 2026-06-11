<?php
    session_start();
    require_once("../admin/agendamento/listar.php");
    require_once("../admin/agendamento/cadastrar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styleAdm.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
    <?php require_once("pages/_layout.php"); ?>
    <div class="corpoInformacao">
        <div class="parteSuperior">
            <h2>Agendamentos</h2>
        </div>
        <div class="parteInformacao">
            <h2>Agandamentos</h2>
            <p>Dashboard > Agendamentos</p>
            <div class="corpoDiv">
                <div class="h2">

                </div>
                <form action=""></form>
                <div class="corpoTabela">
                    <table id="tabela" class="informacaoTabela">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Veiculo</th>
                                <th>Ano do Veículo</th>
                                <th>Tipo de Serviço</th>
                                <th>Data de Visita</th>
                                <th>Horário</th>
                                <th>Comentário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><?= $linha['id'] ?></td>
                                    <td><?= $linha['nome'] ?></td>
                                    <td><?= $linha['email'] ?></td>
                                    <td><?= $linha['telefone'] ?></td>
                                    <td><?= $linha['veiculo'] ?></td>
                                    <td><?= $linha['ano'] ?></td>
                                    <td><?= $linha['servico'] ?></td>
                                    <td><?= $linha['data'] ?></td>
                                    <td><?= $linha['horario'] ?></td>
                                    <td><?= $linha['comentario'] ?></td>
                                    <td>
                                        <a href="../../agendamento/editar.php?id=<?= $linha['id'] ?>"> Editar</a>
                                        <a href="../../agendamento/excluir.php?id=<?= $linha['id'] ?>"> Deletar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="cadastro">

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/datatables.js"></script>
</body>
</html>