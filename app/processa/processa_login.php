<?php

require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = $conn->real_escape_string($_POST['email']);
  $senha_digitada = $conn->real_escape_string($_POST['senha']);

  $sql = "SELECT id, nivel_acesso FROM usuarios WHERE email = '$email' AND senha = '$senha_digitada'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows == 1) {

    $usuario = $result->fetch_assoc();

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_tipo'] = $usuario['nivel_acesso'];

    if ($usuario['nivel_acesso'] == 'admin') {
      header("Location: ../../area_admin.php");
    } else {
      header("Location: ../../area_aluno.php");
    }
    exit();
  } else {
    redirecionar('../../login.php', 'erro', 'Login falhou. E-mail ou senha inválidos!');
  }
}
