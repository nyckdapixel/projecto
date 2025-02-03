<?php


$host = "localhost";
$user = "root";  // Altere se necessário
$pass = "";      // Altere se necessário
$dbname = "gestao_vendas";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
