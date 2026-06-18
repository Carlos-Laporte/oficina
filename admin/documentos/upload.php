<?php
	if (session_status() === PHP_SESSION_NONE) session_start();
	require_once("../../conexao.php");

	if (!isset($_SESSION['adm_id'])) {
		header('Location: ../../login.php');
		exit;
	}

	$erro = '';

	$stmtOrdens = $conn->query("
		SELECT os.id, os.data, os.valor,
			v.marca, v.modelo, v.placa,
			c.nome AS cliente
		FROM os
		LEFT JOIN veiculo v ON os.veiculo_id = v.id
		LEFT JOIN cliente c ON v.cliente_id = c.id
		WHERE os.status = 'Concluído'
		ORDER BY os.id DESC
	");
	$ordensConcluidas = $stmtOrdens->fetchAll(PDO::FETCH_ASSOC);

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$os_id = (int) ($_POST['os_id'] ?? 0);

		if (empty($os_id) || empty($_FILES['arquivo']['name'])) {
			$erro = 'Selecione uma OS concluída e envie um arquivo PDF.';
		} else {
			$stmtOs = $conn->prepare("
				SELECT os.id, c.nome AS cliente, v.marca, v.modelo, v.placa
				FROM os
				LEFT JOIN veiculo v ON os.veiculo_id = v.id
				LEFT JOIN cliente c ON v.cliente_id = c.id
				WHERE os.id = :id AND os.status = 'Concluído'
				LIMIT 1
			");
			$stmtOs->bindValue(':id', $os_id, PDO::PARAM_INT);
			$stmtOs->execute();
			$ordem = $stmtOs->fetch(PDO::FETCH_ASSOC);

			if (!$ordem) {
				$erro = 'Selecione uma OS concluída válida.';
			}
		}

		if (empty($erro)) {
			$arquivo = $_FILES['arquivo'];
			$ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
			$tamanhoMax = 2 * 1024 * 1024;

			if ($ext !== 'pdf') {
				$erro = 'Apenas arquivos PDF são permitidos.';
			} elseif ($arquivo['size'] > $tamanhoMax) {
				$erro = 'O arquivo deve ter no máximo 2 MB.';
			} else {
				$nomeArquivo = uniqid('doc_') . '.pdf';
				$destino = '../../uploads/' . $nomeArquivo;
				if (!is_dir('../../uploads/')) mkdir('../../uploads/', 0755, true);

				if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
					$nome = 'OS #' . $ordem['id'] . ' - ' . ($ordem['cliente'] ?? 'Cliente não informado');
					$sql = "INSERT INTO documentos (nome, arquivo, os_id, criado_em) VALUES (:nome, :arquivo, :os_id, NOW())";
					$stmt = $conn->prepare($sql);
					$stmt->bindValue(':nome', $nome);
					$stmt->bindValue(':arquivo', $nomeArquivo);
					$stmt->bindValue(':os_id', $os_id, PDO::PARAM_INT);

					try {
						$stmt->execute();
						header('Location: paginaDocumentos.php?sucesso=1');
						exit;
					} catch (PDOException $e) {
						$erro = 'Erro ao salvar documento: ' . $e->getMessage();
					}
				} else {
					$erro = 'Erro ao salvar o arquivo.';
				}
			}
		}
	}
?>

<?php if (!empty($erro)): ?>
	<div class="alerta erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="formCadastro">
	<h3>Enviar Documento</h3>
	<form action="upload.php" method="POST" enctype="multipart/form-data">
		<label>OS concluída *</label>
		<select name="os_id" required>
			<option value="">Selecione uma OS concluída</option>
			<?php foreach ($ordensConcluidas as $os): ?>
				<option value="<?= $os['id'] ?>">
					OS #<?= $os['id'] ?> -
					<?= htmlspecialchars($os['cliente'] ?? 'Cliente não informado') ?> -
					<?= htmlspecialchars(trim(($os['marca'] ?? '') . ' ' . ($os['modelo'] ?? '') . ' ' . ($os['placa'] ?? ''))) ?>
				</option>
			<?php endforeach; ?>
		</select>

		<label>Arquivo (PDF, máx. 2 MB) *</label>
		<input type="file" name="arquivo" accept=".pdf" required>

		<div class="formAcoes">
			<button type="submit"><i class="bi bi-upload"></i> Enviar documento</button>
		</div>
	</form>
</div>
