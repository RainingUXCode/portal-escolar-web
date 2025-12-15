<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biblioteca - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #F9FAFB;
    }

    .hero-bg-biblioteca {
      background-image: linear-gradient(rgba(23, 31, 53, 0.7), rgba(14, 74, 165, 0.8)), url('img/biblioteca-banner.jpg');
    }
  </style>
</head>

<body class="bg-white flex flex-col min-h-screen">

  <!-- Cabeçalho -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="container mx-auto">
      <div class="px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="bg-blue-600 rounded-lg w-10 h-10 flex-shrink-0">
            <img class="w-10 h-10 object-contain p-2" src="img/dove.png">
          </div>
          <div>
            <h1 class="text-xl text-gray-800">Colégio Esperança</h1>
            <p class="text-sm text-gray-500">Educando para o futuro</p>
          </div>
        </div>
        <!-- Navegação -->
        <nav class="hidden md:flex items-center gap-2">
          <a href="index.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Home</a>
          <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Notícias</a>
          <a href="biblioteca.php" class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg">Biblioteca</a>
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

        <a href="noticias.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Notícias</a>
        <a href="biblioteca.php" class="block text-center py-2 px-4 text-sm font-semibold text-white bg-gray-900 rounded-lg">Biblioteca</a>
        <a href="login.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Login</a>
      </div>
    </div>
  </header>

  <!-- Conteúdo Principal da Página da Biblioteca -->
  <main class="flex-grow">
    <section class="hero-bg-biblioteca bg-cover bg-center text-white">
      <div class="container mx-auto px-6 py-32 md:py-48 text-center">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-4xl md:text-5xl leading-tight">Biblioteca Escolar</h1>
          <p class="mt-4 text-lg md:text-xl max-w-2xl mx-auto">Um espaço dedicado ao conhecimento com mais de 15.000 livros disponíveis para consulta e empréstimo.</p>
        </div>
      </div>
    </section>

    <!-- Seção de Informações Rápidas -->
    <section class="py-16 md:py-24 bg-white">
      <div class="container mx-auto px-6 max-w-6xl">
        <!-- Grid de Informações -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Card Horário -->
          <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <img class="w-6 h-6" src="img/hora.svg">
              </div>
              <div>
                <h3 class="text-m font-semibold text-gray-900">Horário</h3>
                <p class="text-gray-600">Segunda a Sexta</p>
              </div>
            </div>
            <p class="text-m text-gray-800 mt-4">8h às 18h</p>
            <p class="text-sm text-gray-500">Sábados: 8h às 12h</p>
          </div>
          <!-- Card Localização -->
          <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <img class="w-6 h-6" src="img/local.svg">
              </div>
              <div>
                <h3 class="text-m font-semibold text-gray-900">Localização</h3>
                <p class="text-gray-600">Bloco Principal</p>
              </div>
            </div>
            <p class="text-m text-gray-800 mt-4">2º Andar, Sala 201</p>
            <p class="text-sm text-gray-500">Ao lado da Secretaria</p>
          </div>
          <!-- Card Empréstimo -->
          <div class="bg-white p-6 rounded-xl border border-gray-200">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <img class="w-6 h-6" src="img/book.svg">
              </div>
              <div>
                <h3 class="text-m font-semibold text-gray-900">Empréstimo</h3>
                <p class="text-gray-600">Até 3 livros</p>
              </div>
            </div>
            <p class="text-m text-gray-800 mt-4">Prazo de 15 dias</p>
            <p class="text-sm text-gray-500">Renovável por mais 15 dias</p>
          </div>
        </div>

        <div class="mt-16 md:mt-24 text-center">
          <h2 class="text-3xl text-gray-900">Acervo por Categoria</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-8">

            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">3200</p>
              <p class="text-sm">Literatura</p>
            </div>

            <div class="bg-green-100 text-green-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">2800</p>
              <p class="text-sm">Ciências</p>
            </div>

            <div class="bg-purple-100 text-purple-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">1900</p>
              <p class="text-sm">História</p>
            </div>

            <div class="bg-orange-100 text-orange-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">1500</p>
              <p class="text-sm">Matemática</p>
            </div>

            <div class="bg-pink-100 text-pink-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">2100</p>
              <p class="text-sm">Idiomas</p>
            </div>

            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg border border-gray-200">
              <p class="font-semibold">1200</p>
              <p class="text-sm">Artes</p>
            </div>
          </div>
        </div>

        <!-- Livros em Destaque -->
        <div class="mt-16 md:mt-24 text-center">
          <h2 class="text-3xl text-gray-900">Livros em Destaque</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-8">
            <!-- Livro 1 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-left transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
              <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                <img src="img/domcasmurro.jpg" class="h-40 object-contain">
              </div>
              <h3 class="text-m font-semibold text-gray-900 mt-4">Dom Casmurro</h3>
              <p class="text-sm text-gray-500">Machado de Assis</p>
              <span class="inline-block font-semibold bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mt-3">Literatura</span>
            </div>
            <!-- Livro 2 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-left transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
              <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                <img src="img/sapiens.jpg" class="h-40 object-contain">
              </div>
              <h3 class="text-m font-semibold text-gray-900 mt-4">Sapiens</h3>
              <p class="text-sm text-gray-500">Yuval Noah Harari</p>
              <span class="inline-block font-semibold bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full mt-3">História</span>
            </div>
            <!-- Livro 3 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-left transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
              <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                <img src="img/cosmos.jpg" class="h-40 object-contain">
              </div>
              <h3 class="text-m font-semibold text-gray-900 mt-4">Cosmos</h3>
              <p class="text-sm text-gray-500">Carl Sagan</p>
              <span class="inline-block font-semibold bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full mt-3">Ciências</span>
            </div>
            <!-- Livro 4 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-left transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
              <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                <img src="img/pequenoprincipe.jpg" class="h-40 object-contain">
              </div>
              <h3 class="text-m font-semibold text-gray-900 mt-4">O Pequeno Príncipe</h3>
              <p class="text-sm text-gray-500">Antoine de Saint-Exupéry</p>
              <span class="inline-block font-semibold bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full mt-3">Literatura</span>
            </div>
          </div>
        </div>

        <!-- Como Acessar -->
        <div class="mt-16 md:mt-24">
          <div class="bg-blue-50 p-8 rounded-xl border border-blue-200">
            <h3 class="text-xl font-semibold text-blue-800">Como Acessar a Biblioteca</h3>
            <ol class="mt-4 space-y-3">
              <li class="flex items-center gap-3">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center justify-center">1</span>
                <span class="text-gray-700">Faça login no Portal do Estudante com sua matrícula</span>
              </li>
              <li class="flex items-center gap-3">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center justify-center">2</span>
                <span class="text-gray-700">Pesquise o livro desejado no sistema da biblioteca</span>
              </li>
              <li class="flex items-center gap-3">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center justify-center">3</span>
                <span class="text-gray-700">Dirija-se à biblioteca com seu cartão de estudante</span>
              </li>
              <li class="flex items-center gap-3">
                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white text-sm font-bold rounded-full flex items-center justify-center">4</span>
                <span class="text-gray-700">Registre o empréstimo no balcão de atendimento</span>
              </li>
            </ol>
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