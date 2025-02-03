<?php

include 'conexao.php';

// Verifica se o usuário está logado

// Verifica se um cliente foi selecionado
if (!isset($_GET['id'])) {
    header("Location: clientes.php");
    exit();
}

$cliente_id = $_GET['id'];

// Busca os dados do cliente
$cliente = $conn->query("SELECT * FROM clientes WHERE id = $cliente_id")->fetch_assoc();

// Adicionar Produto
if (isset($_POST['adicionar_produto'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO produtos (cliente_id, nome, preco, quantidade, descricao) 
            VALUES ('$cliente_id', '$nome', '$preco', '$quantidade', '$descricao')";
    $conn->query($sql);
}

// Editar Produto
if (isset($_POST['editar_produto'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE produtos SET nome='$nome', preco='$preco', quantidade='$quantidade', descricao='$descricao' 
            WHERE id='$id' AND cliente_id='$cliente_id'";
    $conn->query($sql);
}

// Remover Produto
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    $sql = "DELETE FROM produtos WHERE id='$id' AND cliente_id='$cliente_id'";
    $conn->query($sql);
}

// Buscar produtos do cliente
$produtos = $conn->query("SELECT * FROM produtos WHERE cliente_id = $cliente_id");

// Calcular total de vendas do cliente
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
    <title>Gerenciamento de Produtos - <?= $cliente['nome'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">Gerenciamento de Produtos - <?= $cliente['nome'] ?></h2>

    <!-- Formulário para adicionar ou editar produtos -->
    <form method="POST">
        <input type="hidden" name="id" id="produto_id">
        <div class="mb-3">
            <label>Nome do Produto:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" id="preco" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descrição:</label>
            <textarea name="descricao" id="descricao" class="form-control"></textarea>
        </div>
        <button type="submit" name="adicionar_produto" class="btn btn-success">Adicionar Produto</button>
        <button type="submit" name="editar_produto" class="btn btn-primary d-none" id="btn-editar">Salvar Edição</button>
        <button type="button" class="btn btn-secondary" onclick="limparFormulario()">Cancelar</button>
    </form>

    <hr>

    <!-- Lista de Produtos do Cliente -->
    <h3>Lista de Produtos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nome'] ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <td><?= $row['descricao'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" 
                                onclick="editarProduto(<?= $row['id'] ?>, '<?= $row['nome'] ?>', <?= $row['preco'] ?>, <?= $row['quantidade'] ?>, '<?= $row['descricao'] ?>')">
                            Editar
                        </button>
                        <a href="?id=<?= $cliente_id ?>&remover=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="text-end">Total de Vendas: <strong>R$ <?= number_format($total_vendas, 2, ',', '.') ?></strong></h4>

    <a href="index_cliente.php?id=<?= $cliente_id ?>" class="btn btn-secondary">Voltar</a>
</div>

<script>
function editarProduto(id, nome, preco, quantidade, descricao) {
    document.getElementById('produto_id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('preco').value = preco;
    document.getElementById('quantidade').value = quantidade;
    document.getElementById('descricao').value = descricao;

    document.querySelector("[name='adicionar_produto']").classList.add('d-none');
    document.getElementById("btn-editar").classList.remove('d-none');
}

function limparFormulario() {
    document.getElementById('produto_id').value = '';
    document.getElementById('nome').value = '';
    document.getElementById('preco').value = '';
    document.getElementById('quantidade').value = '';
    document.getElementById('descricao').value = '';

    document.querySelector("[name='adicionar_produto']").classList.remove('d-none');
    document.getElementById("btn-editar").classList.add('d-none');
}
</script>

</body>
</html>
