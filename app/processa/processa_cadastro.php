<?php

require_once '../includes/funcoes.php';
require_once '../includes/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome           = $conn->real_escape_string(trim($_POST['nome']));
    $email          = $conn->real_escape_string(trim($_POST['email']));
    $matricula      = $conn->real_escape_string(trim($_POST['matricula']));
    $data_nascimento = $_POST['data_nascimento'];
    $endereco       = $conn->real_escape_string(trim($_POST['endereco']));
    $senha          = $conn->real_escape_string($_POST['senha']);
    $nivel_acesso   = 'aluno';

    if (empty($nome) || empty($email) || empty($matricula) || empty($data_nascimento) || empty($endereco) || empty($senha)) {
        redirecionar('../../cadastro.php', 'erro', 'Todos os campos são obrigatórios.');
    }

    $sql_check = "SELECT COUNT(*) AS count FROM usuarios WHERE email = '$email' OR matricula = '$matricula'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();
    $count = $row_check['count'];

    if ($count > 0) {
        $conn->close();
        redirecionar('../../cadastro.php', 'erro', 'E-mail ou Matrícula já cadastrado(a).');
    }

    $sql_insert = "INSERT INTO usuarios (nome, email, senha, matricula, data_nascimento, endereco, nivel_acesso) 
                   VALUES ('$nome', '$email', '$senha', '$matricula', '$data_nascimento', '$endereco', '$nivel_acesso')";

    if ($conn->query($sql_insert)) {
        $conn->close();
        redirecionar('../../login.php', 'sucesso', 'Cadastro realizado! Faça login para entrar.');
    } else {
        $error_message = $conn->error;
        $conn->close();
        redirecionar('../../cadastro.php', 'erro', 'Erro ao salvar o cadastro. Detalhe: ' . $error_message);
    }
} else {
    redirecionar('../../cadastro.php', 'erro', 'Acesso inválido ao formulário.');
}
