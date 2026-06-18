<?php
	if (session_status() === PHP_SESSION_NONE) session_start();
	require_once("../../conexao.php");

	if (!isset($_SESSION['adm_id'])) {
		header('Location: ../../login.php');
		exit;
	}

	$stmt = $conn->query("
		SELECT d.id, d.nome, d.arquivo, d.criado_em, d.os_id,
			   os.status, os.data AS data_os,
			   v.marca, v.modelo, v.placa,
			   c.nome AS cliente
		FROM documentos d
		LEFT JOIN os ON d.os_id = os.id
		LEFT JOIN veiculo v ON os.veiculo_id = v.id
		LEFT JOIN cliente c ON v.cliente_id = c.id
		ORDER BY d.id DESC
	");
	$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_GET['sucesso'])): ?>
	<div class="alerta sucesso">Documento enviado com sucesso!</div>
<?php endif; ?>

<table id="tabela" class="informacaoTabela">
	<thead>
		<tr>
			<th>#</th>
			<th>OS</th>
			<th>Cliente</th>
			<th>Veículo</th>
			<th>Documento</th>
			<th>Criado em</th>
			<th>Ações</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($docs as $d): ?>
			<tr>
				<td><?= $d['id'] ?></td>
				<td>
					<?php if (!empty($d['os_id'])): ?>
						<strong>OS #<?= htmlspecialchars($d['os_id']) ?></strong><br>
						<small><?= htmlspecialchars($d['status'] ?? '-') ?></small>
					<?php else: ?>
						<small>Sem OS vinculada</small>
					<?php endif; ?>
				</td>
				<td><?= htmlspecialchars($d['cliente'] ?? 'Cliente não informado') ?></td>
				<td><?= htmlspecialchars(trim(($d['marca'] ?? '') . ' ' . ($d['modelo'] ?? '') . ' (' . ($d['placa'] ?? '') . ')')) ?></td>
				<td>
					<?php if (!empty($d['arquivo'])): ?>
						<a href="../../uploads/<?= urlencode($d['arquivo']) ?>" target="_blank" class="documentoLink" title="Abrir documento PDF">
							<i class="bi bi-file-earmark-pdf-fill"></i>
							<span>
								<strong>Abrir PDF</strong>
								<small>Documento da OS</small>
							</span>
						</a>
					<?php else: ?>
						-
					<?php endif; ?>
				</td>
				<td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($d['criado_em']))) ?></td>
				<td class="acoes">
					<a href="excluir.php?id=<?= $d['id'] ?>" class="excluir" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o documento #<?= $d['id'] ?>?')"><i class="bi bi-trash-fill"></i></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
