<?php

require_once '../includes/funcoes.php';
require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id'])) {
  redirecionar('../../area_aluno.php', 'erro', 'Método de acesso ou sessão inválida.');
}

$enquete_id = (int)$_POST['enquete_id'];
$opcao_id = (int)$_POST['opcao_id'];
$usuario_id = (int)$_SESSION['usuario_id'];

if (!$enquete_id || !$opcao_id || !$usuario_id) {
  redirecionar('../../area_aluno.php', 'erro', 'Dados de votação incompletos ou inválidos.');
}

$sql_check = "SELECT id FROM votos WHERE enquete_id = $enquete_id AND usuario_id = $usuario_id";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
  $conn->close();
  redirecionar('../../area_aluno.php', 'erro', 'Você já votou nesta enquete.');
}

$sql_insert = "INSERT INTO votos (enquete_id, opcao_id, usuario_id) VALUES ($enquete_id, $opcao_id, $usuario_id)";

if ($conn->query($sql_insert)) {
  // SUCESSO
  $conn->close();
  redirecionar('../../area_aluno.php', 'sucesso', 'Seu voto foi registrado com sucesso!');
} else {
  // ERRO
  $conn->close();
  redirecionar('../../area_aluno.php', 'erro', 'Ocorreu um erro ao registrar seu voto: ' . $conn->error);
}
