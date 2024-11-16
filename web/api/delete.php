<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Erro ao conectar com o banco de dados: " . $e->getMessage()]));
}

// Recebe os dados do corpo da requisição
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // Prepara a query de exclusão
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Usuário deletado com sucesso!"]);
    } else {
        echo json_encode(["error" => "Erro ao deletar o usuário."]);
    }
} else {
    echo json_encode(["error" => "ID do usuário não fornecido."]);
}
