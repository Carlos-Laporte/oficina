<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../../conexao.php");

if (!isset($_SESSION['adm_id'])) {
	header('Location: ../../login.php');
	exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
	header('Location: paginaDocumentos.php');
	exit;
}

$stmt = $conn->prepare("SELECT arquivo FROM documentos WHERE id = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$doc = $stmt->fetch(PDO::FETCH_ASSOC);

if ($doc) {
	if (!empty($doc['arquivo']) && file_exists('../../uploads/' . $doc['arquivo'])) {
		@unlink('../../uploads/' . $doc['arquivo']);
	}

	$del = $conn->prepare("DELETE FROM documentos WHERE id = :id");
	$del->bindValue(':id', $id);
	$del->execute();
}

header('Location: paginaDocumentos.php');
exit;
?>
