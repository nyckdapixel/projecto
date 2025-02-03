<?php

include 'conexao.php';



?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Painel Administrativo</h2>

    <!-- Botão para criar cliente -->
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do Cliente" required>
        <input type="text" name="telefone" placeholder="Telefone" required>
        <button type="submit" name="criar_cliente" class="btn">Criar Cliente</button>
    </form>

    <a href="clientes.php" class="btn">Gerenciar Clientes</a>
    <a href="financeiro.php" class="btn">Gerenciar Financeiro</a>
    <a href="logout.php" class="btn btn-danger">Sair</a>
</div>

<?php
// Criar novo cliente
if (isset($_POST['criar_cliente'])) {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO clientes (nome, telefone) VALUES ('$nome', '$telefone')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cliente criado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao criar cliente!');</script>";
    }
}


// Adicionar Produto
    if (isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO produtos (nome, preco, quantidade, descricao) VALUES ('$nome', '$preco', '$quantidade', '$descricao')";
    $conn->query($sql);

    
}

// Editar Produto
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE produtos SET nome='$nome', preco='$preco', quantidade='$quantidade', descricao='$descricao' WHERE id='$id'";
    $conn->query($sql);
}

// Remover Produto
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    $sql = "DELETE FROM produtos WHERE id='$id'";
    $conn->query($sql);
}

// Buscar produtos para exibição
$result = $conn->query("SELECT * FROM produtos");

// Calcular total de vendas (preço * quantidade)
$total_vendas = 0;
$total_result = $conn->query("SELECT SUM(preco * quantidade) AS total FROM produtos");
if ($row = $total_result->fetch_assoc()) {
    $total_vendas = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Vendas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: url('natureza.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-end">
    <a href="logout.php" class="btn btn-danger">Sair</a>

    </div>

    <h2 class="text-center">Gerenciamento de Produtos</h2>

    <!-- Formulário de Cadastro e Edição -->
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
        <button type="submit" name="adicionar" class="btn btn-success">Adicionar Produto</button>
        <button type="submit" name="editar" class="btn btn-primary d-none" id="btn-editar">Salvar Edição</button>
        <button type="button" class="btn btn-secondary" onclick="limparFormulario()">Cancelar</button>
    </form>

    <hr>

    <!-- Tabela de Produtos -->
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nome'] ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <td><?= $row['descricao'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarProduto(<?= $row['id'] ?>, '<?= $row['nome'] ?>', <?= $row['preco'] ?>, <?= $row['quantidade'] ?>, '<?= $row['descricao'] ?>')">Editar</button>
                        <a href="?remover=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h4 class="text-end">Total de Vendas: <strong>R$ <?= number_format($total_vendas, 2, ',', '.') ?></strong></h4>
</div>

<script>
function editarProduto(id, nome, preco, quantidade, descricao) {
    document.getElementById('produto_id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('preco').value = preco;
    document.getElementById('quantidade').value = quantidade;
    document.getElementById('descricao').value = descricao;

    document.querySelector("[name='adicionar']").classList.add('d-none');
    document.getElementById("btn-editar").classList.remove('d-none');
}

function limparFormulario() {
    document.getElementById('produto_id').value = '';
    document.getElementById('nome').value = '';
    document.getElementById('preco').value = '';
    document.getElementById('quantidade').value = '';
    document.getElementById('descricao').value = '';

    document.querySelector("[name='adicionar']").classList.remove('d-none');
    document.getElementById("btn-editar").classList.add('d-none');
}
</script>

</body>
</html>
