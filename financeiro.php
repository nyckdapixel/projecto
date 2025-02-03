<?php
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    
}

// Buscar lista de clientes para o dropdown
$clientes = $conn->query("SELECT * FROM clientes");

// Adicionar nova entrada financeira
if (isset($_POST['adicionar_financeiro'])) {
    $cliente_id = $_POST['cliente_id'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO financeiro (cliente_id, descricao, valor) VALUES ('$cliente_id', '$descricao', '$valor')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Movimentação adicionada com sucesso!'); window.location.href='financeiro.php';</script>";
    } else {
        echo "<script>alert('Erro ao adicionar movimentação financeira!');</script>";
    }
}

// Buscar todas as movimentações financeiras
$financeiro = $conn->query("SELECT f.*, c.nome AS cliente_nome FROM financeiro f JOIN clientes c ON f.cliente_id = c.id ORDER BY f.data_movimento DESC");

// Calcular total financeiro
$total_financeiro = 0;
$total_result = $conn->query("SELECT SUM(valor) AS total FROM financeiro");
if ($row = $total_result->fetch_assoc()) {
    $total_financeiro = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento Financeiro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">Gerenciamento Financeiro</h2>

    <!-- Formulário para adicionar movimentação financeira -->
    <form method="POST">
        <div class="mb-3">
            <label>Cliente:</label>
            <select name="cliente_id" class="form-control" required>
                <option value="">Selecione um Cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Valor:</label>
            <input type="number" step="0.01" name="valor" class="form-control" required>
        </div>
        <button type="submit" name="adicionar_financeiro" class="btn btn-success">Adicionar</button>
    </form>

    <hr>

    <!-- Lista de movimentações financeiras -->
    <h3>Histórico Financeiro</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $financeiro->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['cliente_nome'] ?></td>
                    <td><?= $row['descricao'] ?></td>
                    <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                    <td><?= $row['data_movimento'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="text-end">Total Financeiro: <strong>R$ <?= number_format($total_financeiro, 2, ',', '.') ?></strong></h4>

    <a href="index.php" class="btn btn-secondary">Voltar</a>
</div>

</body>
</html>
