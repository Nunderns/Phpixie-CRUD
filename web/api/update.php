<?php
require_once(__DIR__ . '/../../vendor/autoload.php');


$pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$nome = $data['nome'];
$email = $data['email'];

$query = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, updated_at = NOW() WHERE id = :id");
$query->execute(['id' => $id, 'nome' => $nome, 'email' => $email]);

echo json_encode(['status' => 'success']);
