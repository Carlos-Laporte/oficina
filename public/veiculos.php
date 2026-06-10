<?php
    session_start();
    require_once("../admin/veiculos/listar.php");
    require_once("../admin/veiculos/cadastrar.php");
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
    <?php require_once("pages/_layout.php"); ?>
    <div class="corpoInformacao">
        <div class="parteSuperior">
            <h2>Clientes</h2>
        </div>
        <div class="parteInformacao">
            <h2>Clientes</h2>
            <p>Dashboard > Clientes</p>
            <div class="corpoDiv">
                <div class="h2">

                </div>
                <form action=""></form>
                <div class="corpoTabela">
                    <table id="userTable" class="dashboard_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultado as $linha): ?>
                                <tr>
                                    <td><?= $linha['id'] ?></td>
                                    <td><?= $linha['nome'] ?></td>
                                    <td><?= $linha['cpf'] ?></td>
                                    <td><?= $linha['telefone'] ?></td>
                                    <td><?= $linha['email'] ?></td>
                                    <td>
                                        <a href="../../clientes/editar.php?id=<?= $linha['id'] ?>"> Editar</a>
                                        <a href="../../clientes/excluir.php?id=<?= $linha['id'] ?>"> Editar</a>
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
</body>
</html>