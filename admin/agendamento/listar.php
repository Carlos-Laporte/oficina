<?php
    require_once("../../conexao.php");

    if(isset($_SESSION['adm_id'])){

        $stmt = $conn->query("SELECT * FROM agendamento");

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