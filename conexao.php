<?php

$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'oficina';

try {
    $conn = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"Erro ao conectar com o banco de dados."], JSON_UNESCAPED_UNICODE);
    exit;
}
