<?php

require_once(__DIR__.'/../vendor/autoload.php');

use Project\Framework;

$framework = new Framework();
$framework->registerDebugHandlers();

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=atividade_api', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

// Função para buscar dados da tabela 'usuarios'
function getUserData($pdo)
{
    $stmt = $pdo->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para renderizar os dados em HTML
function renderUserTable($users)
{
    echo "<h2>Tabela: Usuários</h2>";
    if (count($users) > 0) {
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Ações</th>
              </tr>";

        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['nome']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "<td>{$user['updated_at']}</td>";
            echo "<td>
                    <button onclick=\"updateUser(" . $user['id'] . ")\">Editar</button>
                    <button onclick=\"deleteUser(" . $user['id'] . ")\">Excluir</button>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Sem registros nesta tabela.</p>";
    }
    echo "<button onclick=\"createUser()\">Adicionar Novo Registro</button>";
}

// Buscar dados dos usuários
$users = getUserData($pdo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - PHPixie</title>
    <script>
        function createUser() {
            const nome = prompt("Digite o nome:");
            const email = prompt("Digite o email:");
            if (nome && email) {
                fetch(`api/create.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nome, email })
                }).then(() => location.reload());
            }
        }

        function updateUser(id) {
            const nome = prompt("Digite o novo nome:");
            const email = prompt("Digite o novo email:");
            if (nome && email) {
                fetch(`api/update.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, nome, email })
                }).then(() => location.reload());
            }
        }

        function deleteUser(id) {
    if (confirm("Tem certeza que deseja excluir este registro?")) {
        fetch(`api/delete.php`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Erro ao deletar usuário:', error);
            alert('Erro ao deletar usuário. Verifique o console para mais detalhes.');
        });
    }
}

    </script>
</head>
<body>
    <h1>CRUD de Usuários</h1>
    <?php renderUserTable($users); ?>
</body>
</html>
