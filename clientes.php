<?php
include 'conexao.php';

// Buscar clientes
$clientes = $conn->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Clientes</h2>

    <table>
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
                        <a href="index_cliente.php?id=<?= $cliente['id'] ?>" class="btn">Gerenciar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
