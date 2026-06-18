<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    require_once("../../conexao.php");

    if (!isset($_SESSION['adm_id'])) {
        header('Location: ../../login.php');
        exit;
    }

    $stmt = $conn->query("
        SELECT os.id, os.descricao, os.valor, os.data, os.status, os.comprovante,
               v.marca, v.modelo, v.placa,
               c.nome AS cliente
        FROM os
        LEFT JOIN veiculo v ON os.veiculo_id = v.id
        LEFT JOIN cliente c ON v.cliente_id = c.id
        ORDER BY os.id DESC
    ");
    $ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $badges = [
        'Orçamento'    => 'badge-orcamento',
        'Aprovado'     => 'badge-aprovado',
        'Em andamento' => 'badge-andamento',
        'Concluído'    => 'badge-concluido',
    ];
?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alerta sucesso">OS cadastrada com sucesso!</div>
<?php endif; ?>

<div class="acoesTopo">
    <a href="cadastrar.php" class="btnNovo"><i class="bi bi-plus-lg"></i> Nova OS</a>
</div>

<table id="tabela" class="informacaoTabela">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Veículo</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ordens as $os): ?>
            <tr>
                <td><?= $os['id'] ?></td>
                <td><?= htmlspecialchars($os['cliente'] ?? '-') ?></td>
                <td><?= htmlspecialchars(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '') . ' (' . ($os['placa'] ?? '') . ')') ?></td>
                <td><?= htmlspecialchars(mb_strimwidth($os['descricao'], 0, 60, '...')) ?></td>
                <td>R$ <?= number_format($os['valor'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y', strtotime($os['data'])) ?></td>
                <td>
                    <span class="badge <?= $badges[$os['status']] ?? '' ?>">
                        <?= htmlspecialchars($os['status']) ?>
                    </span>
                </td>
                <td class="acoes">
                    <?php if (!empty($os['comprovante'])): ?>
                        <a href="../../uploads/<?= urlencode($os['comprovante']) ?>" target="_blank" class="btn-pdf" title="Ver PDF">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </a>
                    <?php endif; ?>
                    <a href="editar.php?id=<?= $os['id'] ?>" class="editar" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="excluir.php?id=<?= $os['id'] ?>" class="excluir" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir a OS #<?= $os['id'] ?>?')">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
