<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notícias - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #F9FAFB;
    }

    .hero-bg-noticias {
      background-image: linear-gradient(rgba(23, 31, 53, 0.7), rgba(14, 74, 165, 0.8)), url('img/noticias-banner.jpg');
    }
  </style>
</head>

<body class="bg-white flex flex-col min-h-screen">

  <!-- Cabeçalho -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="container mx-auto">
      <div class="px-6 py-4 flex justify-between items-center">

        <!-- Logo e Nome da Escola -->
        <div class="flex items-center gap-3">
          <div class="bg-blue-600 rounded-lg w-10 h-10 flex-shrink-0">
            <img class="w-10 h-10 object-contain p-2" src="img/dove.png">
            </svg>
          </div>
          <div>
            <h1 class="text-xl text-gray-800">Colégio Esperança</h1>
            <p class="text-sm text-gray-500">Educando para o futuro</p>
          </div>
        </div>
        <!-- Navegação -->
        <nav class="hidden md:flex items-center gap-2">
          <a href="index.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Home</a>
          <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg">Notícias</a>
          <a href="biblioteca.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Biblioteca</a>
          <a href="login.php" class="px-4 py-2 text-sm font-semibold text-gray-800 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Login</a>
        </nav>
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

  <!-- Seção Hero de Noticias -->
  <section class="hero-bg-noticias bg-cover bg-center text-white">
    <div class="container mx-auto px-6 py-32 md:py-48 text-center">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl leading-tight">Notícias e Eventos</h1>
        <p class="mt-4 text-lg md:text-xl max-w-2xl mx-auto">Fique por dentro de tudo que acontece no Colégio Esperança.</p>
      </div>
    </div>
  </section>

  <!-- Conteúdo Principal da Página de Notícias -->
  <main class="flex-grow">
    <section class="py-16 md:py-24 bg-white">
      <div class="container mx-auto px-6">
        <!-- Título da Seção -->
        <div class="text-center max-w-3xl mx-auto">
          <h2 class="text-3xl md:text-4xl text-gray-900">Ano 2025</h2>
          <p class="mt-4 text-lg text-gray-600">Marque no calendário o próximo evento!</p>
        </div>

        <!-- Tábua de Notícias -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16 max-w-6xl mx-auto">

          <!-- Card de Notícia 1 -->
          <div class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img class="w-full h-64 object-cover" src="img/ciencias.jpg">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Feira de Ciências 2025 - Inscrições Abertas</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/calendar.svg">
                  25 de Novembro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/pessoa.svg">
                  Coordenação Esperança
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">As inscrições para a Feira de Ciências estão abertas! Este ano o tema é por votação na área do aluno. Vote e participe com seu projeto inovador até 02 de Dezembro.</p>
            </div>
          </div>

          <!-- Card de Notícia 2 -->
          <div class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img class="w-full h-64 object-cover" src="img/backtoschool.jpg">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Bem-vindos ao Novo Ano Letivo!</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/calendar.svg">
                  14 de Fevereiro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/pessoa.svg">
                  Coordenação Esperança
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">Estamos felizes em receber todos os alunos para mais um ano de aprendizado e crescimento. Que este ano seja repleto de conquistas e novas amizades!</p>
            </div>
          </div>

          <!-- Card de Notícia 3 -->
          <div class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img class="w-full h-64 object-cover" src="img/esporte.jpg">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Torneio Esportivo Inter-Classes</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/calendar.svg">
                  13 de Outubro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/pessoa.svg">
                  Professor Allyson
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">Prepare-se para o nosso torneio anual! Futebol, vôlei, basquete e muito mais. Inscrições com o professor de Educação Física.</p>
            </div>
          </div>

          <!-- Card de Notícia 4 -->
          <div class="bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <img class="w-full h-64 object-cover" src="img/estudos.jpg">
            <div class="p-6 flex flex-col flex-grow">
              <h3 class="text-xl text-gray-900">Dicas de Estudo: Como se Preparar para as Provas</h3>
              <div class="flex gap-4 text-sm text-gray-500 mt-2">
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/calendar.svg">
                  09 de Setembro de 2025
                </span>
                <span class="flex items-center gap-1.5">
                  <img class="w-4 h-4" src="img/pessoa.svg">
                  Professora Carolina
                </span>
              </div>
              <p class="mt-4 text-gray-600 flex-grow">Organize seu tempo, faça resumos, pratique exercícios e não deixe para última hora! Lembre-se: a biblioteca está aberta de segunda a sexta, das 8h às 18h.</p>
            </div>
          </div>

        </div>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <footer class="bg-gray-900 text-white mt-auto">
    <div class="container mx-auto px-6 py-6 text-center text-sm">
      <p>Colégio Esperança © 2025. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
    }
  </script>
</body>

</html>