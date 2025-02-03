<?php
session_start();
include 'conexao.php';




// Verifica se um cliente foi selecionado
if (!isset($_GET['id'])) {
    header("Location: clientes.php");
    exit();
}

$cliente_id = $_GET['id'];

// Busca os dados do cliente
$cliente = $conn->query("SELECT * FROM clientes WHERE id = $cliente_id")->fetch_assoc();

// Busca produtos do cliente
$produtos = $conn->query("SELECT * FROM produtos WHERE cliente_id = $cliente_id");

// Busca financeiro do cliente
$financeiro = $conn->query("SELECT * FROM financeiro WHERE cliente_id = $cliente_id");

// Calcula total de vendas
$total_vendas = 0;
$total_result = $conn->query("SELECT SUM(preco * quantidade) AS total FROM produtos WHERE cliente_id = $cliente_id");
if ($row = $total_result->fetch_assoc()) {
    $total_vendas = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cliente - <?= $cliente['nome'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">Gerenciamento de <?= $cliente['nome'] ?></h2>

    <h3>Produtos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nome'] ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <td><?= $row['descricao'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Financeiro</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $financeiro->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['descricao'] ?></td>
                    <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                    <td><?= $row['data_movimento'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="text-end">Total de Vendas: <strong>R$ <?= number_format($total_vendas, 2, ',', '.') ?></strong></h4>

    <a href="clientes.php" class="btn btn-secondary">Voltar</a>
</div>

</body>
</html>
