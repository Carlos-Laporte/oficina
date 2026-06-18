<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $stmt = $conn->query("SELECT v.*, c.nome AS cliente_nome FROM veiculo v LEFT JOIN cliente c ON v.cliente_id = c.id ORDER BY v.id DESC");
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta sucesso">Veículo cadastrado com sucesso!</div>
<?php endif; ?>

<div class="acoesTopo">
    <a href="cadastrar.php" class="btnNovo"><i class="bi bi-plus-lg"></i> Novo Veículo</a>
</div>

<table id="tabela" class="informacaoTabela">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Placa</th>
            <th>Ano</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($veiculos as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= htmlspecialchars($v['cliente_nome'] ?? '—') ?></td>
                <td><?= htmlspecialchars($v['marca']) ?></td>
                <td><?= htmlspecialchars($v['modelo']) ?></td>
                <td><?= htmlspecialchars($v['placa']) ?></td>
                <td><?= htmlspecialchars($v['ano']) ?></td>
                <td class="acoes">
                    <a href="editar.php?id=<?= $v['id'] ?>" class="editar" title="Editar"><i class="bi bi-pencil-square"></i></a>
                    <a href="excluir.php?id=<?= $v['id'] ?>" class="excluir" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o veículo #<?= $v['id'] ?>?')"><i class="bi bi-trash-fill"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>