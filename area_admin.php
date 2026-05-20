<?php

require_once 'app/includes/funcoes.php';
require_once 'app/includes/conexao.php';

$admin_data = null;
$enquetes_destaque = [];
$enquetes_admin = [];

// 1. CHECAGEM DE ACESSO
proteger_pagina(['admin']);

$id_admin_logado = (int)$_SESSION['usuario_id'];

// 2. BUSCAR DADOS DO ADMINISTRADOR
$sql_admin_data = "SELECT nome, email, nivel_acesso FROM usuarios WHERE id = {$id_admin_logado}";
$result_admin_data = $conn->query($sql_admin_data);

if ($result_admin_data->num_rows == 1) {
  $admin_data = $result_admin_data->fetch_assoc();
} else {
  redirecionar('login.php', 'erro', 'Sua conta de administrador não foi encontrada. Faça login novamente.');
  exit();
}

// 3. BUSCA E AGRUPAMENTO DA ESTRUTURA DA ENQUETE DE DESTAQUE (Card Principal)
$temp_destaque_struct = [];
$sql_destaque = "
    SELECT 
        e.id AS enquete_id, 
        e.pergunta, 
        o.id AS opcao_id, 
        o.texto_opcao
    FROM 
        enquetes e
    JOIN 
        opcoes_enquete o ON e.id = o.enquete_id
    WHERE 
        e.destaque = 1 AND e.ativa = 1
    ORDER BY 
        e.id, o.id
";
$result_destaque = $conn->query($sql_destaque);

if ($result_destaque && $result_destaque->num_rows > 0) {
  while ($row = $result_destaque->fetch_assoc()) {
    $enquete_id = $row['enquete_id'];

    if (!isset($temp_destaque_struct[$enquete_id])) {
      $temp_destaque_struct[$enquete_id] = [
        'id' => $enquete_id,
        'pergunta' => htmlspecialchars($row['pergunta']),
        'opcoes' => [],
      ];
    }

    $temp_destaque_struct[$enquete_id]['opcoes'][] = [
      'id' => $row['opcao_id'],
      'texto' => htmlspecialchars($row['texto_opcao']),
    ];
  }
}

// 3.1. CALCULA RESULTADOS PARA A ENQUETE DE DESTAQUE
$enquetes_destaque = [];
foreach ($temp_destaque_struct as $enquete_id => $enquete) {
  $dados_votos = obter_resultados_enquete($conn, $enquete_id);

  $enquete['total_votos'] = $dados_votos['total_votos'];
  $enquete['resultados'] = $dados_votos['resultados'];

  $enquetes_destaque[] = $enquete;
}


// 4. BUSCA E AGRUPAMENTO DE ESTRUTURA DE ENQUETES NORMAIS
$enquetes_temp_admin = [];

$sql_enquetes_admin = "
    SELECT 
        e.id AS enquete_id, 
        e.pergunta, 
        e.data_criacao, 
        o.id AS opcao_id, 
        o.texto_opcao
    FROM 
        enquetes e
    JOIN 
        opcoes_enquete o ON e.id = o.enquete_id
    WHERE 
        e.destaque = 0 AND e.ativa = 1 
    ORDER BY 
        e.data_criacao DESC, e.id, o.id
";

$result_enquetes_admin = $conn->query($sql_enquetes_admin);

if ($result_enquetes_admin && $result_enquetes_admin->num_rows > 0) {
  while ($row = $result_enquetes_admin->fetch_assoc()) {
    $enquete_id = $row['enquete_id'];

    if (!isset($enquetes_temp_admin[$enquete_id])) {
      $enquetes_temp_admin[$enquete_id] = [
        'id' => $enquete_id,
        'pergunta' => htmlspecialchars($row['pergunta']),
        'data_criacao' => $row['data_criacao'],
        'opcoes' => [], // Estrutura base
      ];
    }

    $enquetes_temp_admin[$enquete_id]['opcoes'][] = [
      'id' => $row['opcao_id'],
      'texto' => htmlspecialchars($row['texto_opcao'])
    ];
  }
}

// 5. CALCULA RESULTADOS E ALIMENTA O ARRAY FINAL
$enquetes_admin = []; // Redefine o array final

foreach ($enquetes_temp_admin as $enq_id => $enquete) {

  $dados_votos = obter_resultados_enquete($conn, $enq_id);

  $enquete['total_votos'] = $dados_votos['total_votos'];
  $enquete['resultados'] = $dados_votos['resultados'];

  // Adiciona ao array final de listagem
  $enquetes_admin[] = $enquete;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área do Adm - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #F9FAFB;
    }

    .poll-progress {
      transition: width 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-gray-50">

  <!-- Cabeçalho -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="container mx-auto">
      <div class="px-8 py-4 flex justify-between items-center">
        <!-- Logo e Nome da Escola -->
        <a href="index.php" class="flex items-center gap-3">
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center p-2">
            <img class="w-full h-full object-contain" src="assets/img/dove.png">
          </div>
          <div>
            <h1 class="text-xl font-regular text-gray-800">Colégio Esperança</h1>
            <p class="text-sm text-gray-500">Educando para o futuro</p>
          </div>
        </a>

        <!-- Menu Desktop-->
        <div class="hidden md:flex items-center space-x-2">
          <a href="index.php" class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg">Home</a>
          <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Notícias</a>
          <a href="biblioteca.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Biblioteca</a>
          <span class="text-gray-400 mx-1">|</span>
          <a href="painel_admin.php" class="flex items-center gap-1.5 text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-semibold ml-2">
            <img class="w-4 h-4" src="assets/img/usuario.svg">
            Admin
          </a>
          <a href="index.php" class="text-gray-800 hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-semibold flex items-center gap-1.5 bg-white backdrop-blur-sm rounded-lg border border-gray-200">
            <img class="w-4 h-4" src="assets/img/sair.svg">
            Sair
          </a>
        </div>

        <!-- Menu Mobile (Ícone) -->
        <div class="md:hidden">
          <button id="menu-toggle" class="text-gray-800">
            <img class="w-11 h-11 object-contain p-2" src="assets/img/toggle.png">
          </button>
        </div>
      </div>
      <!-- Navegação Mobile -->
      <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
        <a href="index.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Home</a>
        <a href="noticias.php" class="block text-center py-2 px-4 text-sm font-semibold text-white bg-gray-900 rounded-lg">Notícias</a>
        <a href="biblioteca.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Biblioteca</a>
        <a href="login.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Login</a>
      </div>
    </div>
  </header>

  <!-- Conteúdo Principal -->
  <main class="container mx-auto px-6 lg:px-20 py-8">

    <!-- Saudação -->
    <div class="mb-8">
      <h1 class="text-3xl font-regular text-gray-900">Olá, Administrador! 👋</h1>
      <p class="text-lg text-gray-600">Portal do Administrador</p>
    </div>

    <!-- Enquete em Destaque -->
    <?php
    if (!empty($enquetes_destaque)):
      $destaque = $enquetes_destaque[0];
    ?>

      <div class="bg-gradient-to-r from-blue-800 to-indigo-500 text-white p-6 rounded-xl shadow-lg mb-8 px-10">

        <div class="flex items-start gap-3 mb-4">
          <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
            <img class="w-6 h-6" src="assets/img/enquete.svg">
          </div>
          <div>
            <span class="text-lg font-regular block">Enquete em Destaque ✨</span>
            <span class="flex items-center font-regular gap-1 text-sm opacity-90 mt-1">
              <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
              Resultados ao vivo
            </span>
          </div>
        </div>

        <h3 class="text-xl font-semibold mb-4"><?php echo $destaque['pergunta']; ?></h3>

        <div class="bg-white text-gray-900 p-6 rounded-lg shadow-md relative z-10">

          <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-4" id="poll-options-destaque">

            <?php foreach ($destaque['resultados'] as $opcao): ?>
              <?php
              $progress_class = ($opcao['contagem'] > 0) ? 'bg-blue-600' : 'bg-gray-400';
              ?>

              <div class="poll-option-destaque bg-white p-4 rounded-lg border-2 border-gray-200 transition-all duration-300">
                <div class="flex justify-between items-start mb-1">
                  <span class="text-m font-regular text-gray-900"><?php echo $opcao['texto']; ?></span>
                  <span class="text-xl font-semibold text-gray-500"><?php echo $opcao['porcentagem']; ?>%</span>
                </div>

                <p class="text-sm text-gray-500 mb-2"><?php echo $opcao['contagem']; ?> votos</p>

                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="<?php echo $progress_class; ?> h-2 rounded-full poll-progress" style="width: <?php echo $opcao['porcentagem']; ?>%"></div>
                </div>
              </div>
            <?php endforeach; ?>

          </div>

          <div class="border-t border-gray-200 pt-4 grid grid-cols-2 items-center gap-4">
            <span class="text-m text-gray-600 justify-self-start"><?php echo $destaque['total_votos']; ?> votos totais</span>

            <button disabled class="bg-gray-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold shadow-sm justify-self-end cursor-not-allowed">
              Visualizando Resultados
            </button>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 px-2">
      <!-- Coluna Esquerda: Últimas Notícias -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-regular text-gray-900">Últimas Notícias</h2>
          <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-200 rounded">Ver todas</a>
        </div>
        <div class="space-y-6">
          <!-- Notícia 1 -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img class="w-full h-80 object-cover" src="assets/img/ciencias.jpg">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Feira de Ciências 2025 - Inscrições Abertas</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/calendar.svg">
                  25 de Novembro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/pessoa.svg">
                  Coordenação Esperança
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">As inscrições para a Feira de Ciências estão abertas! Este ano o tema é por votação na área do aluno. Vote e participe com seu projeto inovador até 02 de Dezembro.</p>
            </div>
          </div>
          <!-- Notícia 2 -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img src="assets/img/esporte.jpg" class="w-full h-80 object-cover">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Torneio Esportivo Inter-Classes</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/calendar.svg">
                  13 de Outubro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/pessoa.svg">
                  Professor Allyson
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">Prepare-se para o nosso torneio anual! Futebol, vôlei, basquete e muito mais. Inscrições com o professor de Educação Física.</p>
            </div>
          </div>
          <!-- Notícia 3 -->
          <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img src="assets/img/estudos.jpg" class="w-full h-80 object-cover">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Dicas de Estudo: Como se preparar para as Provas</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/calendar.svg">
                  09 de Setembro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="assets/img/pessoa.svg">
                  Professora Carolina
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">Organize seu tempo, faça resumos, pratique exercícios e não deixe para última hora! Lembre-se: a biblioteca está aberta de segunda a sexta, das 8h às 18h.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Coluna Direita: Enquetes Ativas -->
      <div>
        <h2 class="text-2xl font-regul text-gray-900 mb-4">Enquetes Ativas</h2>
        <div class="space-y-6">
          <?php foreach ($enquetes_admin as $enquete): ?>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
              <div class="flex items-start gap-3 mb-3">
                <img class="w-6 h-6" src="assets/img/voto.svg">
                <div>
                  <h3 class="text-base font-semibold text-gray-800"><?php echo htmlspecialchars($enquete['pergunta']); ?></h3>
                  <p class="text-sm text-gray-500 mt-1">Criado em: <?php echo date('d/m/Y', strtotime($enquete['data_criacao'])); ?></p>
                </div>
              </div>

              <div class="space-y-3 mt-2">
                <?php
                if (isset($enquete['resultados'])):
                  foreach ($enquete['resultados'] as $opcao):
                    $progress_class = ($opcao['contagem'] > 0) ? 'bg-blue-600' : 'bg-gray-400';
                ?>
                    <div class="space-y-1">
                      <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700"><?php echo htmlspecialchars($opcao['texto']); ?> (<?php echo htmlspecialchars($opcao['contagem']); ?> votos)</span>
                        <span class="font-semibold text-gray-600"><?php echo htmlspecialchars($opcao['porcentagem']); ?>%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="<?php echo $progress_class; ?> h-2 rounded-full" style="width: <?php echo htmlspecialchars($opcao['porcentagem']); ?>%"></div>
                      </div>
                    </div>
                  <?php endforeach;
                else: ?>
                  <p class="text-sm text-gray-500">Nenhuma opção encontrada ou voto registrado.</p>
                <?php endif; ?>
              </div>

              <div class="text-xs text-gray-500 mt-4 pt-2 border-t border-gray-100">
                Total: <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($enquete['total_votos'] ?? 0); ?></span> votos
              </div>
            </div>
          <?php endforeach; ?>
        </div>


        <!-- Aniversariantes do Mês -->
        <h2 class="text-2xl font-regular text-gray-900 mt-12">Aniversariantes do Mês</h2>
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mt-6">
          <div class="flex items-start gap-3 mb-3">
            <img class="w-6 h-6" src="assets/img/birthday-cake.png">
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos aniversários:</h3>
            </div>
          </div>
          <ul class="space-y-1 divide-y divide-gray-100">
            <li class="flex items-center justify-between text-sm py-2 bg-blue-100 px-4">
              <span class="flex items-center gap-2">
                <span class="text-gray-800"> Ana Clara Silva</span>
              </span>
              <span class="text-gray-600">Dia 05/11</span>
            </li>
            <li class="flex items-center justify-between text-sm py-2 px-4">
              <span class="flex items-center gap-2">
                <span class="text-gray-800">Bruno Gomes</span>
              </span>
              <span class="text-gray-600">Dia 12/11</span>
            </li>
            <li class="flex items-center justify-between text-sm py-2 bg-blue-100 px-4">
              <span class="flex items-center gap-2">
                <span class="text-gray-800">Mariana Oliveira</span>
              </span>
              <span class="text-gray-600">Dia 21/11</span>
            </li>
            <li class="flex items-center justify-between text-sm py-2 px-4">
              <span class="flex items-center gap-2">
                <span class="text-gray-800">Lucas Pereira</span>
              </span>
              <span class="text-gray-600">Dia 28/11</span>
            </li>
          </ul>
          <p class="text-sm text-gray-500 mt-4 text-center">Parabéns aos aniversariantes!</p>
        </div>
      </div>
    </div>
    </div>

  </main>

  <!-- Rodapé -->
  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-6 py-6 text-center text-sm">
      <p>Colégio Esperança © 2025. Todos os direitos reservados.</p>
    </div>
  </footer>


  <script>
    // Espera o DOM carregar antes de adicionar listeners
    document.addEventListener('DOMContentLoaded', () => {
      const menuToggle = document.getElementById('menu-toggle');
      const mobileMenu = document.getElementById('mobile-menu');

      // Verifica se os elementos existem antes de adicionar o listener
      if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
          mobileMenu.classList.toggle('hidden');
        });
      } else {
        console.error("Elementos do menu mobile não encontrados!");
      }

      // --- LÓGICA DE ENQUETE  ---

      // 1. Lógica para SELEÇÃO
      const pollRadios = document.querySelectorAll('.space-y-2 input[type="radio"]');
      pollRadios.forEach(radio => {
        radio.addEventListener('change', (event) => {
          const pollCard = event.target.closest('.bg-white.rounded-xl');
          if (!pollCard) return;

          pollCard.querySelectorAll('label').forEach(label => {
            label.classList.remove('bg-blue-100', 'border-blue-500', 'font-semibold');
            label.classList.add('bg-gray-50', 'border-gray-200');
          });

          const selectedLabel = event.target.closest('label');
          if (selectedLabel) {
            selectedLabel.classList.add('bg-blue-100', 'border-blue-500', 'font-semibold');
            selectedLabel.classList.remove('bg-gray-50', 'border-gray-200');
          }
        });
      });

      // 2. Lógica para VOTAR (confirmação)
      const voteButtons = document.querySelectorAll('button.vote-button');
      voteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
          const voteButton = event.target.closest('button');
          const pollCard = voteButton.closest('.bg-white.rounded-xl');
          if (!pollCard) return;

          const selectedRadio = pollCard.querySelector('input[type="radio"]:checked');

          if (selectedRadio) {
            const pollOptions = pollCard.querySelectorAll('input[type="radio"]');
            const pollTitle = pollCard.querySelector('h3').textContent.trim();
            const selectedText = selectedRadio.nextElementSibling.textContent.trim();

            console.log(`Voto confirmado para: ${selectedText} na enquete ${pollTitle}`);

            pollOptions.forEach(r => {
              r.disabled = true;
              r.closest('label').classList.add('cursor-not-allowed', 'opacity-70');
              r.closest('label').classList.remove('hover:bg-gray-100');
            });

            voteButton.disabled = true;

            const buttonIcon = voteButton.querySelector('svg');
            if (buttonIcon) buttonIcon.remove();
            voteButton.textContent = 'Voto Registrado';

            voteButton.classList.remove('bg-gray-900', 'hover:bg-gray-800');
            voteButton.classList.add('bg-gray-400', 'cursor-not-allowed');

          } else {
            console.log("Por favor, selecione uma opção antes de votar.");
          }
        });
      });

      // --- LÓGICA PARA ENQUETE EM DESTAQUE (CARDÁPIO) ---

      const destaqueOptionsContainer = document.getElementById('poll-options-destaque');
      const destaqueVoteButton = document.getElementById('vote-button-destaque');

      if (destaqueOptionsContainer && destaqueVoteButton) {

        let selectedDestaqueOption = null;

        // 1. Lógica para SELEÇÃO (visual) - Enquete Destaque
        const destaqueOptions = destaqueOptionsContainer.querySelectorAll('.poll-option-destaque');

        destaqueOptions.forEach(option => {
          option.addEventListener('click', (event) => {

            destaqueOptions.forEach(opt => {
              opt.classList.remove('border-blue-500', 'scale-105', 'shadow-lg');
              opt.classList.add('border-gray-200');
            });

            const clickedOption = event.currentTarget;
            clickedOption.classList.add('border-blue-500', 'scale-105', 'shadow-lg');
            clickedOption.classList.remove('border-gray-200');


            selectedDestaqueOption = clickedOption;
          });
        });

        // 2. Lógica para VOTAR (confirmação) - Enquete Destaque
        destaqueVoteButton.addEventListener('click', () => {


          if (selectedDestaqueOption) {
            const selectedText = selectedDestaqueOption.querySelector('.text-m').textContent.trim();
            console.log(`Voto confirmado para (Destaque): ${selectedText}`);


            destaqueOptions.forEach(opt => {
              opt.classList.add('opacity-70', 'cursor-not-allowed');
              opt.classList.remove('hover:border-blue-400', 'scale-105', 'shadow-lg');

              opt.replaceWith(opt.cloneNode(true));
            });

            let selectedIndex = -1;
            destaqueOptions.forEach((opt, index) => {
              if (opt === selectedDestaqueOption) {
                selectedIndex = index;
              }
            });

            const allOptionsAfterClone = destaqueOptionsContainer.querySelectorAll('.poll-option-destaque');
            if (selectedIndex > -1 && allOptionsAfterClone[selectedIndex]) {
              allOptionsAfterClone[selectedIndex].classList.add('border-blue-500', 'border-2');
              allOptionsAfterClone[selectedIndex].classList.remove('border-gray-200');
            }


            destaqueVoteButton.disabled = true;
            destaqueVoteButton.textContent = 'Voto Registrado';
            destaqueVoteButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            destaqueVoteButton.classList.add('bg-gray-400', 'cursor-not-allowed');

            const buttonIcon = destaqueVoteButton.querySelector('svg');
            if (buttonIcon) buttonIcon.remove();

          } else {
            console.log("Por favor, selecione uma opção antes de votar na enquete em destaque.");
          }
        });
      }

    });
  </script>

</body>

</html>
