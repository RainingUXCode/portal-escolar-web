<?php
require_once 'includes/funcoes.php';

$mensagem = '';
if (isset($_SESSION['sucesso'])) {
  $mensagem = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">' . $_SESSION['sucesso'] . '</div>';
  unset($_SESSION['sucesso']);
} elseif (isset($_SESSION['erro'])) {
  $mensagem = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . $_SESSION['erro'] . '</div>';
  unset($_SESSION['erro']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, rgba(23, 31, 53, 0.3), rgba(240, 245, 255, 0.7) 50%, rgba(14, 74, 165, 0.219))
    }

    input::placeholder {
      color: #A1A1AA;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 py-8">

  <div class="w-full max-w-2xl">
    <!-- Botão Voltar -->
    <a href="index.php" class="inline-flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-gray-900 mb-4 bg-white backdrop-blur-sm px-3 py-2 rounded-lg border border-gray-200">
      <img class="w-4 h-4" src="img/voltar.svg">
      Voltar
    </a>

    <!-- Card de Cadastro -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full">
      <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center mb-4">
          <img class="w-12 h-12 object-contain p-2" src="img/dove.png">
        </div>
        <h2 class="text-xl font-semibold text-gray-900">Cadastro de Estudante</h2>
        <p class="text-gray-500 mt-1">Preencha todos os dados para criar sua conta</p>
      </div>

      <!-- Formulário -->
      <form action="processa\processa_cadastro.php" method="POST" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Campo Nome Completo -->
          <div>
            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo:</label>
            <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <!-- Campo Matrícula -->
          <div>
            <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" placeholder="Ex: 2024001" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Campo E-mail -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="seu.email@escola.com" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
          <!-- Campo Data de Nascimento -->
          <div>
            <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-500">
          </div>
        </div>

        <!-- Campo Endereço -->
        <div>
          <label for="endereco" class="block text-sm font-medium text-gray-700 mb-1">Endereço:</label>
          <input type="text" id="endereco" name="endereco" placeholder="Rua, número, bairro" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Campo Senha -->
        <div>
          <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha:</label>
          <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required minlength="6" class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Botão Criar Conta -->
        <button type="submit" class="w-full bg-gray-900 text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-800 transition duration-300">
          Criar Conta
        </button>
      </form>

      <!-- Separador -->
      <div class="border-t border-gray-200 my-6"></div>

      <!-- Link Faça Login -->
      <p class="text-center text-m text-gray-600 mt-6">
        Já tem uma conta?
        <a href="login.php" class="font-semibold text-blue-600 hover:underline">Faça login</a>
      </p>

    </div>
  </div>

</body>

</html>