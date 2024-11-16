<?php
requrequire_once(__DIR__ . '/../../vendor/autoload.php');


$pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$query = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
$query->execute(['id' => $id]);

echo json_encode(['status' => 'success']);
