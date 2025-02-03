<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: clientes.php");
   
}


$cliente_id = $_GET['id'];
$cliente = $conn->query("SELECT * FROM clientes WHERE id = $cliente_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Adicionando o CSS externo -->
</head>
<body>

<div class="container">
    <h2>Painel de <?= $cliente['nome'] ?></h2>

    <a href="produtos.php?id=<?= $cliente_id ?>" class="btn">Gerenciar Produtos</a>
    <a href="financeiro.php?id=<?= $cliente_id ?>" class="btn">Gerenciar Financeiro</a>
    <a href="clientes.php" class="btn">Voltar</a>
</div>

</body>
</html>
