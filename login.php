<?php
session_start();
include 'conexao.php';

// Verifica login
if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['usuario_nome'] = $row['usuario'];

        if ($usuario == 'fatima') {
            // Redireciona para o painel completo (Fátima)
            header('Location: index.php'); 
        } elseif ($usuario == 'user') {
            // Redireciona para a página de financeiro com limitações (User)
            header('Location: financeiro.php'); 
        } else {
            echo "Usuário não autorizado!";
        }
        exit();
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}

// Redireciona para login se não estiver logado
if (!isset($_SESSION['usuario_nome'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2 class="text-center">Login</h2>
            <?php if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>
            <form method="POST">
                <input type="text" name="usuario" placeholder="Usuário" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit" name="login">Entrar</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}
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
    <?php if ($_SESSION['usuario_nome'] == 'fatima') { ?>
        <a href="clientes.php" class="btn">Gerenciar Clientes</a>
        <a href="financeiro.php" class="btn">Gerenciar Financeiro</a>
    <?php } ?>
    <a href="logout.php" class="btn btn-danger">Sair</a>
</div>

</body>
</html>
