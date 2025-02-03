<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
// Buscar clientes
$clientes = $conn->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">Clientes</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cliente = $clientes->fetch_assoc()): ?>
                <tr>
                    <td><?= $cliente['nome'] ?></td>
                    <td><?= $cliente['telefone'] ?></td>
                    <td>
                        <a href="index_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-primary btn-sm">Gerenciar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Botão Voltar -->
    <div class="text-end">
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </div>
</div>

</body>
</html>
