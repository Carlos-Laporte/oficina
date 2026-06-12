<?php
    require_once("../../conexao.php");

    if(isset($_SESSION['adm_id'])){

        $stmt = $conn->query("SELECT 
                                veiculo.id,
                                veiculo.placa,
                                veiculo.modelo,
                                veiculo.marca,
                                veiculo.ano,
                                veiculo.cor,
                                cliente.nome AS nome_cliente
                            FROM veiculo
                            INNER JOIN cliente ON cliente.id = veiculo.cliente_id");

        $resultado = $stmt->fetchALL();

    } else{
        header('Location: ../admin/login.php');
        exit();
    }
?>

<table id="tabela" class="informacaoTabela">
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
                    <a href="editar.php?id=<?= $linha['id'] ?>"> Editar</a>
                    <a href="excluir.php?id=<?= $linha['id'] ?>"> Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>