<?php
require_once(__DIR__ . '/../../vendor/autoload.php');


$pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');

$data = json_decode(file_get_contents('php://input'), true);
$nome = $data['nome'];
$email = $data['email'];

$query = $pdo->prepare("INSERT INTO usuarios (nome, email, created_at, updated_at) VALUES (:nome, :email, NOW(), NOW())");
$query->execute(['nome' => $nome, 'email' => $email]);

echo json_encode(['status' => 'success']);
