<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header('Location: login.php');
    exit();
}

// Adicionar entrada financeira
if (isset($_POST['adicionar_entrada'])) {
    $descricao = $_POST['descricao_entrada'];
    $valor = $_POST['valor_entrada'];
    $cliente_id = $_SESSION['usuario_id']; // Associando ao usuário logado

    $sql = "INSERT INTO financeiro (cliente_id, descricao, valor) VALUES ('$cliente_id', '$descricao', '$valor')";
    $conn->query($sql);
}

// Adicionar saída financeira
if (isset($_POST['adicionar_saida'])) {
    $descricao = $_POST['descricao_saida'];
    $valor = $_POST['valor_saida'];
    $cliente_id = $_SESSION['usuario_id']; // Associando ao usuário logado

    $sql = "INSERT INTO saidas (cliente_id, descricao, valor) VALUES ('$cliente_id', '$descricao', '$valor')";
    $conn->query($sql);
}

// Editar entrada financeira
if (isset($_POST['editar_entrada'])) {
    $id = $_POST['id_entrada'];
    $descricao = $_POST['descricao_entrada'];
    $valor = $_POST['valor_entrada'];

    $sql = "UPDATE financeiro SET descricao='$descricao', valor='$valor' WHERE id='$id' AND cliente_id='{$_SESSION['usuario_id']}'";
    $conn->query($sql);
}

// Editar saída financeira
if (isset($_POST['editar_saida'])) {
    $id = $_POST['id_saida'];
    $descricao = $_POST['descricao_saida'];
    $valor = $_POST['valor_saida'];

    $sql = "UPDATE saidas SET descricao='$descricao', valor='$valor' WHERE id='$id' AND cliente_id='{$_SESSION['usuario_id']}'";
    $conn->query($sql);
}

// Remover entrada financeira
if (isset($_GET['remover_entrada'])) {
    $id = $_GET['remover_entrada'];
    $sql = "DELETE FROM financeiro WHERE id='$id' AND cliente_id='{$_SESSION['usuario_id']}'";
    $conn->query($sql);
}

// Remover saída financeira
if (isset($_GET['remover_saida'])) {
    $id = $_GET['remover_saida'];
    $sql = "DELETE FROM saidas WHERE id='$id' AND cliente_id='{$_SESSION['usuario_id']}'";
    $conn->query($sql);
}

// Buscar as entradas financeiras do usuário logado
$entradas_result = $conn->query("SELECT * FROM financeiro WHERE cliente_id='{$_SESSION['usuario_id']}'");

// Buscar as saídas financeiras do usuário logado
$saidas_result = $conn->query("SELECT * FROM saidas WHERE cliente_id='{$_SESSION['usuario_id']}'");

// Calcular o total das entradas financeiras
$total_entradas_result = $conn->query("SELECT SUM(valor) AS total_entradas FROM financeiro WHERE cliente_id='{$_SESSION['usuario_id']}'");
$total_entradas = $total_entradas_result->fetch_assoc()['total_entradas'];

// Calcular o total das saídas financeiras
$total_saidas_result = $conn->query("SELECT SUM(valor) AS total_saidas FROM saidas WHERE cliente_id='{$_SESSION['usuario_id']}'");
$total_saidas = $total_saidas_result->fetch_assoc()['total_saidas'];

$total = $total_entradas - $total_saidas; // Total = Entradas - Saídas
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Gerenciamento Financeiro</h2>

    <!-- Formulário de Adicionar Entrada -->
    <form method="POST">
        <h3>Adicionar Entrada</h3>
        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao_entrada" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Valor:</label>
            <input type="number" step="0.01" name="valor_entrada" class="form-control" required>
        </div>
        <button type="submit" name="adicionar_entrada" class="btn btn-success">Adicionar Entrada</button>
    </form>

    <hr>

    <!-- Formulário de Adicionar Saída -->
    <form method="POST">
        <h3>Adicionar Saída</h3>
        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao_saida" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Valor:</label>
            <input type="number" step="0.01" name="valor_saida" class="form-control" required>
        </div>
        <button type="submit" name="adicionar_saida" class="btn btn-danger">Adicionar Saída</button>
    </form>

    <hr>

    <!-- Exibindo Total de Entradas e Saídas -->
    <h3>Total de Entradas: R$ <?= number_format($total_entradas, 2, ',', '.') ?></h3>
    <h3>Total de Saídas: R$ <?= number_format($total_saidas, 2, ',', '.') ?></h3>
    <h3>Saldo Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

    <hr>

    <!-- Tabela de Entradas Financeiras -->
    <h3>Lista de Entradas Financeiras</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $entradas_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['descricao'] ?></td>
                    <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="?remover_entrada=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Tabela de Saídas Financeiras -->
    <h3>Lista de Saídas Financeiras</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $saidas_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['descricao'] ?></td>
                    <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                    <td>
                        <a href="?remover_saida=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">Voltar</a>
</div>

</body>
</html>
