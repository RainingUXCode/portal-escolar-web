<?php
require_once 'includes/funcoes.php';
require_once 'includes/conexao.php';

proteger_pagina(['aluno']);

$id_aluno_logado = (int)$_SESSION['usuario_id'];
$enquetes_ativas = [];
$enquetes_temp = [];
$enquetes_destaque = [];
$enquetes_normais = [];


// 1. CONSULTA PRINCIPAL

$sql_enquetes = "
    SELECT 
        e.id AS enquete_id, 
        e.pergunta, 
        e.destaque,  
        o.id AS opcao_id, 
        o.texto_opcao
    FROM 
        enquetes e
    JOIN 
        opcoes_enquete o ON e.id = o.enquete_id
    WHERE 
        e.ativa = 1
    ORDER BY 
        e.destaque DESC, e.id, o.id
";

$result_enquetes = $conn->query($sql_enquetes);

if ($result_enquetes && $result_enquetes->num_rows > 0) {
  while ($row = $result_enquetes->fetch_assoc()) {
    $enquete_id = $row['enquete_id'];

    if (!isset($enquetes_temp[$enquete_id])) {
      $enquetes_temp[$enquete_id] = [
        'id' => $enquete_id,
        'pergunta' => htmlspecialchars($row['pergunta']),
        'destaque' => $row['destaque'],
        'opcoes' => [],
      ];
    }

    $enquetes_temp[$enquete_id]['opcoes'][] = [
      'id' => $row['opcao_id'],
      'texto' => htmlspecialchars($row['texto_opcao'])
    ];
  }
}


// 2. PROCESSAMENTO: Checagem de voto, cálculo de resultados e separação
foreach ($enquetes_temp as $enquete_id => $enquete) {

  // A) CHECAGEM DE VOTO
  $voto_aluno = checar_voto_usuario($conn, $enquete_id, $id_aluno_logado);

  $enquete['votou'] = $voto_aluno !== null;
  $enquete['voto_aluno'] = $voto_aluno;

  // B) CÁLCULO DE VOTOS TOTAIS
  $total_votos = calcular_total_votos($conn, $enquete_id);

  // C) RECUPERAÇÃO DETALHADA E CÁLCULO DE PORCENTAGEM
  $sql_resultados = "SELECT v.opcao_id, COUNT(v.id) AS contagem FROM votos v WHERE v.enquete_id = {$enquete_id} GROUP BY v.opcao_id";

  $votos_por_opcao = [];
  $result_resultados = $conn->query($sql_resultados);

  while ($row = $result_resultados->fetch_assoc()) {
    $votos_por_opcao[$row['opcao_id']] = $row['contagem'];
  }

  $enquete['total_votos'] = $total_votos;
  $enquete['resultados'] = [];

  foreach ($enquete['opcoes'] as $opcao) {
    $contagem = $votos_por_opcao[$opcao['id']] ?? 0;
    $porcentagem = $total_votos > 0 ? round(($contagem / $total_votos) * 100) : 0;

    $enquete['resultados'][] = [
      'opcao_id' => $opcao['id'],
      'texto' => $opcao['texto'],
      'contagem' => $contagem,
      'porcentagem' => $porcentagem
    ];
  }

  // 3. SEPARAÇÃO FINAL
  if ($enquete['destaque'] == 1) {
    $enquetes_destaque[$enquete_id] = $enquete;
  } else {
    $enquetes_normais[$enquete_id] = $enquete;
  }
}

// A conexão será fechada automaticamente ao fim da execução do script.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área do Aluno - Colégio Esperança</title>
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
            <img class="w-full h-full object-contain" src="img/dove.png">
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
          <a href="perfil_aluno.php" class="flex items-center gap-1.5 text-gray-700 hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-semibold ml-2">
            <img class="w-4 h-4" src="img/usuario.svg">
            Perfil
          </a>
          <a href="index.php" class="text-gray-800 hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-semibold flex items-center gap-1.5 bg-white backdrop-blur-sm rounded-lg border border-gray-200">
            <img class="w-4 h-4" src="img/sair.svg">
            Sair
          </a>
        </div>

        <!-- Menu Mobile (Ícone) -->
        <div class="md:hidden">
          <button id="menu-toggle" class="text-gray-800">
            <img class="w-11 h-11 object-contain p-2" src="img/toggle.png">
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
      <h1 class="text-3xl font-regular text-gray-900">Olá, bem-vindo(a)! 👋</h1>
      <p class="text-lg text-gray-600">Portal do Estudante</p>
    </div>

    <!-- Enquete em Destaque -->
    <?php if (!empty($enquetes_destaque)): ?>
      <?php $destaque = reset($enquetes_destaque);
      ?>

      <div class="bg-gradient-to-r from-blue-800 to-indigo-500 text-white p-6 rounded-xl shadow-lg mb-8 px-10">

        <div class="flex items-start gap-3 mb-4">
          <div class="w-10 h-10 bg-white/30 rounded-lg flex items-center justify-center flex-shrink-0">
            <img class="w-6 h-6" src="img/enquete.svg">
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

          <form id="form-destaque-<?php echo $destaque['id']; ?>" method="POST" action="processa/processa_voto.php">
            <input type="hidden" name="enquete_id" value="<?php echo $destaque['id']; ?>">
            <input type="hidden" name="opcao_id" id="opcao-selecionada-<?php echo $destaque['id']; ?>" value="">

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-4" id="poll-options-destaque">

              <?php foreach ($destaque['resultados'] as $opcao): ?>
                <?php
                $is_voted = $destaque['votou'] && ($opcao['opcao_id'] == $destaque['voto_aluno']);
                $border_class = $is_voted ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-400';
                $bar_color = ($destaque['total_votos'] > 0 || $is_voted) ? 'bg-blue-600' : 'bg-gray-400';
                $disabled_class = $destaque['votou'] ? 'cursor-default' : 'cursor-pointer';
                ?>

                <div
                  class="poll-option-destaque p-4 rounded-lg border-2 transition-all duration-300 shadow-sm <?php echo $border_class; ?> <?php echo $disabled_class; ?>"
                  data-enquete-id="<?php echo $destaque['id']; ?>"
                  data-opcao-id="<?php echo $opcao['opcao_id']; ?>"
                  onclick="<?php echo $destaque['votou'] ? '' : 'votarDestaque(this)'; ?>">
                  <div class="flex justify-between items-start mb-1">
                    <span class="text-m font-regular <?php echo $is_voted ? 'text-blue-800 font-semibold' : 'text-gray-900'; ?>">
                      <?php echo $opcao['texto']; ?>
                      <?php if ($is_voted): ?> <span class="text-xs font-bold text-blue-600 ml-1">(Seu Voto)</span> <?php endif; ?>
                    </span>

                    <span class="text-xl font-semibold text-gray-500">
                      <?php echo $opcao['porcentagem']; ?>%
                    </span>
                  </div>

                  <p class="text-sm text-gray-500 mb-2"><?php echo $opcao['contagem']; ?> votos</p>

                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="<?php echo $bar_color; ?> h-2 rounded-full poll-progress" style="width: <?php echo $opcao['porcentagem']; ?>%"></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
              <span class="text-m text-gray-600 justify-self-start"><?php echo $destaque['total_votos']; ?> votos totais</span>

              <?php if ($destaque['votou']): ?>
                <button disabled class="bg-gray-400 text-white px-4 py-1.5 rounded-md text-sm font-semibold shadow-sm cursor-not-allowed">
                  Voto Registrado
                </button>
              <?php else: ?>
                <button type="button" onclick="enviarVotoDestaque(<?php echo $destaque['id']; ?>)" id="btn-votar-destaque-<?php echo $destaque['id']; ?>" class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition duration-200 shadow-sm">
                  Votar Agora
                </button>
              <?php endif; ?>
            </div>
          </form>

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
          <?php
          $sql_noticias = "SELECT id, titulo, autor, conteudo, imagem_url, data_publicacao FROM noticias ORDER BY data_publicacao DESC LIMIT 3";
          $result_noticias = $conn->query($sql_noticias);
          if ($result_noticias && $result_noticias->num_rows > 0):
            while ($noticia = $result_noticias->fetch_assoc()):
          ?>
              <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <?php if (!empty($noticia['imagem_url'])): ?>
                  <img class="w-full h-80 object-cover" src="<?php echo htmlspecialchars($noticia['imagem_url']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                <?php else: ?>
                  <div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-500">Sem imagem</div>
                <?php endif; ?>
                <div class="p-6 flex flex-col flex-grow">
                  <h3 class="text-xl text-gray-900"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                  <div class="flex gap-4 text-sm text-gray-500 mt-2">
                    <span class="flex items-center gap-1.5">
                      <img class="w-4 h-4" src="img/calendar.svg">
                      <?php echo date('d/m/Y', strtotime($noticia['data_publicacao'])); ?>
                    </span>
                    <span class="flex items-center gap-1.5">
                      <img class="w-4 h-4" src="img/pessoa.svg">
                      <?php echo htmlspecialchars($noticia['autor']); ?>
                    </span>
                  </div>
                  <p class="mt-4 text-gray-600 flex-grow"><?php echo nl2br(htmlspecialchars(mb_strlen($noticia['conteudo']) > 200 ? mb_substr($noticia['conteudo'], 0, 200) . '...' : $noticia['conteudo'])); ?></p>
                </div>
              </div>
            <?php
            endwhile;
          else:
            ?>
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-gray-600 text-center">
              Nenhuma notícia encontrada.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Coluna Direita: Enquetes Ativas -->
      <div>
        <h2 class="text-2xl font-regul text-gray-900 mb-4">Enquetes Ativas</h2>
        <div class="space-y-6">
          <!-- Enquete 1 -->
          <?php foreach ($enquetes_normais as $enquete): ?>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
              <div class="flex items-start gap-3 mb-3">
                <img class="w-6 h-6" src="img/voto.svg">
                <div>
                  <h3 class="text-base font-semibold text-gray-800"><?php echo $enquete['pergunta']; ?></h3>
                  <p class="text-sm text-gray-500 mt-1">Votação aberta</p>
                </div>
              </div>

              <?php if ($enquete['votou']): ?>
                <p class="text-sm font-medium text-blue-600 mb-3">Voto Registrado. Resultados:</p>

                <div class="space-y-3">
                  <?php foreach ($enquete['resultados'] as $opcao): ?>
                    <?php
                    $is_voted_option = ($opcao['opcao_id'] == $enquete['voto_aluno']);
                    $text_class = $is_voted_option ? 'text-blue-800 font-bold' : 'text-gray-700';
                    $bg_class = $is_voted_option ? 'bg-blue-600' : 'bg-gray-400';
                    ?>

                    <div class="flex flex-col">
                      <div class="flex justify-between text-sm mb-1">
                        <span class="<?php echo $text_class; ?>"><?php echo $opcao['texto']; ?></span>
                        <span class="font-semibold <?php echo $text_class; ?>"><?php echo $opcao['porcentagem']; ?>% (<?php echo $opcao['contagem']; ?> votos)</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div
                          class="poll-progress h-2.5 rounded-full <?php echo $bg_class; ?>"
                          style="width: <?php echo $opcao['porcentagem']; ?>%"></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

                <button disabled class="vote-button mt-4 w-full bg-gray-400 text-white py-2 px-6 rounded-lg text-sm font-semibold flex items-center justify-center gap-1 cursor-not-allowed">
                  Voto Registrado
                </button>

              <?php else: ?>
                <form id="form-normal-<?php echo $enquete['id']; ?>" method="POST" action="processa/processa_voto.php">
                  <input type="hidden" name="enquete_id" value="<?php echo $enquete['id']; ?>">
                  <input type="hidden" name="opcao_id" id="opcao-selecionada-normal-<?php echo $enquete['id']; ?>" value="">

                  <div class="space-y-2">
                    <?php foreach ($enquete['resultados'] as $opcao): ?>
                      <label
                        class="poll-option-normal flex items-center gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100"
                        data-enquete-id="<?php echo $enquete['id']; ?>"
                        data-opcao-id="<?php echo $opcao['opcao_id']; ?>"
                        onclick="selecionarOpcaoNormal(this)">
                        <input type="radio" name="temp_radio_<?php echo $enquete['id']; ?>" class="form-radio text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700"><?php echo $opcao['texto']; ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>

                  <button type="button"
                    id="votar-btn-normal-<?php echo $enquete['id']; ?>"
                    onclick="enviarVotoNormal(<?php echo $enquete['id']; ?>)"
                    class="vote-button mt-4 w-full bg-gray-900 text-white py-2 px-6 rounded-lg text-sm font-semibold hover:bg-gray-800 transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Votar
                  </button>
                </form>

              <?php endif; ?>
            </div>
          <?php endforeach; ?>

        </div>

        <!-- Aniversariantes do Mês -->
        <h2 class="text-2xl font-regular text-gray-900 mt-12">Aniversariantes do Mês</h2>
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mt-6">
          <div class="flex items-start gap-3 mb-3">
            <img class="w-6 h-6" src="img/birthday-cake.png">
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
    // --- LÓGICA DE MENU MOBILE ---
    document.addEventListener('DOMContentLoaded', () => {
      const menuToggle = document.getElementById('menu-toggle');
      const mobileMenu = document.getElementById('mobile-menu');

      if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
          mobileMenu.classList.toggle('hidden');
        });
      }
    });

    // --- LÓGICA DE VOTAÇÃO DE ENQUETE EM DESTAQUE ---

    let selectedOptionIdDestaque = null;

    function votarDestaque(element) {
      const enqueteId = element.getAttribute('data-enquete-id');
      const opcaoId = element.getAttribute('data-opcao-id');
      const form = document.getElementById(`form-destaque-${enqueteId}`);

      if (form.querySelector('button[disabled]')) {
        return;
      }

      form.querySelectorAll('.poll-option-destaque').forEach(opt => {
        opt.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-lg', 'scale-105');
        opt.classList.add('border-gray-200', 'bg-white', 'hover:border-blue-400');
      });

      element.classList.add('border-blue-500', 'bg-blue-50', 'shadow-lg', 'scale-105');
      element.classList.remove('border-gray-200', 'bg-white', 'hover:border-blue-400');
      selectedOptionIdDestaque = opcaoId;
    }

    function enviarVotoDestaque(enqueteId) {
      const form = document.getElementById(`form-destaque-${enqueteId}`);

      if (selectedOptionIdDestaque) {
        document.getElementById(`opcao-selecionada-${enqueteId}`).value = selectedOptionIdDestaque;
        form.submit();
      } else {
        alert("Por favor, selecione uma opção antes de votar!");
      }
    }


    // --- LÓGICA DE VOTAÇÃO DE ENQUETES NORMAIS ---
    const selectedOptionsNormal = {};

    function selecionarOpcaoNormal(element) {
      const enqueteId = element.getAttribute('data-enquete-id');
      const opcaoId = element.getAttribute('data-opcao-id');
      const form = document.getElementById(`form-normal-${enqueteId}`);
      const voteButton = document.getElementById(`votar-btn-normal-${enqueteId}`);

      form.querySelectorAll('.poll-option-normal').forEach(opt => {
        opt.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md');
        opt.classList.add('border-gray-200', 'bg-gray-50', 'hover:bg-gray-100');
        opt.querySelector('input[type="radio"]').checked = false;
      });

      element.classList.add('border-blue-500', 'bg-blue-50');
      element.classList.remove('border-gray-200', 'bg-gray-50', 'hover:bg-gray-100');
      element.querySelector('input[type="radio"]').checked = true;
      selectedOptionsNormal[enqueteId] = opcaoId;
      voteButton.disabled = false;
    }

    function enviarVotoNormal(enqueteId) {
      const form = document.getElementById(`form-normal-${enqueteId}`);
      const selectedOptionId = selectedOptionsNormal[enqueteId];

      if (selectedOptionId) {
        document.getElementById(`opcao-selecionada-normal-${enqueteId}`).value = selectedOptionId;
        form.submit();
      } else {
        alert("Por favor, selecione uma opção antes de votar!");
      }
    }
  </script>

</body>

</html>