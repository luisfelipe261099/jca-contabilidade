<?php
require_once 'config/config.php';
if (!isLoggedIn()) redirect('index.php');
$db = getDB();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM documentos WHERE id=?");
$stmt->execute([$id]);
$doc = $stmt->fetch();

if (!$doc) {
    setError('Documento não encontrado.');
    redirect('documentos.php');
}

$rel = ltrim($doc['caminho_arquivo'], '/');
$full = BASE_PATH . '/' . $rel;
if (!is_file($full)) {
    setError('Arquivo não encontrado no servidor (registro DEMO ou arquivo removido).');
    redirect('documentos.php');
}

$filename = basename($doc['nome_arquivo'] ?: $full);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($full));
readfile($full);
exit;

