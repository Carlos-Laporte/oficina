<?php
    require_once("../../conexao.php");

    if(isset($_SESSION['adm_id'])){

        $stmt = $conn->query("SELECT * FROM cliente");

        $resultado = $stmt->fetchALL();

    } else{
        header('Location: ../admin/login.php');
        exit();
    }
?>

<table id="tabela" class="informacaoTabela">
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
                    <a href="clientes/editar.php?id=<?= $linha['id'] ?>"> Editar</a>
                    <a href="excluir.php?id=<?= $linha['id'] ?>"> Deletar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>