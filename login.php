<?php
require_once 'app/includes/funcoes.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;

      background: linear-gradient(135deg, rgba(23, 31, 53, 0.3), rgba(240, 245, 255, 0.7) 50%, rgba(14, 74, 165, 0.219));
    }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">
  <?php echo exibir_flash_message(); ?>
  <div class="w-full max-w-md">
    <!-- Botão Voltar -->
    <a href="index.php" class="inline-flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-gray-900 mb-4 bg-white backdrop-blur-sm px-3 py-2 rounded-lg border border-gray-200">
      <img class="w-4 h-4" src="assets/img/voltar.svg">
      Voltar
    </a>

    <!-- Card de Login -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-8 w-full">
      <div class="flex flex-col items-center mb-6">
        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center mb-4">
          <img class="w-12 h-12 object-contain p-2" src="assets/img/dove.png">
        </div>
        <h2 class="text-xl font-semibold text-gray-900">Login do Portal</h2>
        <p class="text-gray-500 mt-1">Acesse sua conta para continuar</p>
      </div>

      <!-- Formulário -->
      <form action="app/processa/processa_login.php" method="POST" class="space-y-4">
        <!-- Campo Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail:</label>
          <input type="email" id="email" name="email" placeholder="seu.email@escola.com" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Campo Senha -->
        <div>
          <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha:</label>
          <input type="password" id="senha" name="senha" placeholder="••••••••" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Botão Entrar -->
        <button type="submit" class="w-full bg-gray-900 text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-gray-800 transition duration-300">
          Entrar
        </button>
      </form>
      <!-- Separador -->
      <div class="border-t border-gray-200 my-6"></div>

      <!-- Link Cadastre-se -->
      <p class="text-center text-m text-gray-600 mt-6">
        Não tem uma conta?
        <a href="cadastro.php" class="font-semibold text-blue-600 hover:underline">Cadastre-se</a>
      </p>
    </div>
  </div>
  <script>
    function isvalid() {
      var user = document.loginForm.email.value.trim();
      var pass = document.loginForm.senha.value;

      if (user.length === 0 && pass.length === 0) {
        alert("E-mail e Senha estão vazios!");
        return false;
      } else {
        if (user.length === 0) {
          alert("O campo E-mail está vazio!");
          return false;
        }
        if (pass.length === 0) {
          alert("O campo Senha está vazio!");
          return false;
        }
      }
      return true;
    }
  </script>
</body>

</html>
