<?php
session_start();
include "config.php";

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuario WHERE email='$email' AND senha='$senha'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $usuario = $result->fetch_assoc();

    $_SESSION['id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];

    header("Location: dashboard.php");
    exit;

} else {

    echo "Email ou senha inválidos.";

}
?>