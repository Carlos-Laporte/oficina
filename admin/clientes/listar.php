<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $stmt = $conn->query("SELECT * FROM cliente ORDER BY id DESC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta sucesso">Cliente cadastrado com sucesso!</div>
<?php endif; ?>

<div class="acoesTopo">
    <a href="cadastrar.php" class="btnNovo"><i class="bi bi-plus-lg"></i> Novo Cliente</a>
</div>

<table id="tabela" class="informacaoTabela">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['cpf']) ?></td>
                <td><?= htmlspecialchars($c['telefone']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td class="acoes">
                    <a href="editar.php?id=<?= $c['id'] ?>" class="editar" title="Editar"><i class="bi bi-pencil-square"></i></a>
                    <a href="excluir.php?id=<?= $c['id'] ?>" class="excluir" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o cliente #<?= $c['id'] ?>?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>