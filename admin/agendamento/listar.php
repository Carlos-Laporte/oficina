<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $stmt = $conn->query("SELECT * FROM agendamento ORDER BY id DESC");
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta sucesso">Agendamento cadastrado com sucesso!</div>
<?php endif; ?>

<div class="acoesTopo">
    <a href="cadastrar.php" class="btnNovo"><i class="bi bi-plus-lg"></i> Novo Agendamento</a>
</div>

<table id="tabela" class="informacaoTabela">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Veículo</th>
            <th>Serviço</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agendamentos as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['nome']) ?></td>
                <td><?= htmlspecialchars($a['telefone']) ?></td>
                <td><?= htmlspecialchars($a['veiculo'] . ' ' . $a['ano']) ?></td>
                <td><?= htmlspecialchars($a['servico']) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($a['data']))) ?></td>
                <td><?= htmlspecialchars($a['horario']) ?></td>
                <td class="acoes">
                    <a href="editar.php?id=<?= $a['id'] ?>" class="editar" title="Editar"><i class="bi bi-pencil-square"></i></a>
                    <a href="excluir.php?id=<?= $a['id'] ?>" class="excluir" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o agendamento #<?= $a['id'] ?>?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>