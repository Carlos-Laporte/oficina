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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
</head>
<body>
    <?php require_once("pages/_layout.php"); ?>
    <div class="corpoInformacao">
        <div class="parteSuperior">
            <h2>Veículos</h2>
        </div>
        <div class="parteInformacao">
            <h2>Veículos</h2>
            <p>Dashboard > Veículos</p>
            <div class="conjunto">
                <div class="corpoDivVeiculo">
                    <div class="h2">

                    </div>
                    <div class="corpoTabela">
                        <table id="userTable" class="informacaoTabela">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Ano</th>
                                    <th>Cor</th>
                                    <th>Cliente</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultado as $linha): ?>
                                    <tr>
                                        <td><?= $linha['placa'] ?></td>
                                        <td><?= $linha['modelo'] ?></td>
                                        <td><?= $linha['marca'] ?></td>
                                        <td><?= $linha['ano'] ?></td>
                                        <td><?= $linha['cor'] ?></td>
                                        <td><?= $linha['nome_cliente'] ?></td>
                                        <td>
                                            <a href="../../veiculos/editar.php?id=<?= $linha['id'] ?>"> Editar</a>
                                            <a href="../../veiculos/excluir.php?id=<?= $linha['id'] ?>"> Editar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="cadastro">
                    <h2 class="texto-form"> Cadastrar Novo Veículo</h2>
                    <div class="formConteiner">
                        <form action="veiculos.php" method="POST">
                            <label for="">Clinte</label><br>
                            <select name="cliente_id" id="cliente_id">
                                <option value="">Selecione o cliente</option>
                                <?php foreach($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id'] ?>">
                                        <?= $cliente['nome'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select><br>
                            <label for="">Placa</label><br>
                            <input type="text" name="placa" placeholder="Digite a placa do seu veículo" minlength="7" maxlength="8" required><br>
                            <label for="">Modelo</label><br>
                            <input type="text" name="modelo" placeholder="Digite o modelo" required><br>
                            <label for="">Marca</label><br>
                            <input type="text" name="marca"><br>
                            <label for="">Ano</label><br>
                            <input type="number" name="ano" placeholder="Ano do veículo" min="1900" max="2026" required><br>
                            <label for="">Cor</label><br>
                            <input type="text" name="cor" placeholder="Cor do veículo" required><br>
                            <label for="">Chassi (Opcional)</label><br>
                            <input type="text" name="chassi" placeholder="Digite o número do chassi" minlength="17" maxlength="17" required><br>
                            <button type="submit"> Salvar veículo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="parteInformacao">
            <h2>afdsfasf</h2>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="../assets/js/selectVeiculo.js"></script>
</body>
</html>