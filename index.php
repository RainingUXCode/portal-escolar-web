<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #F9FAFB;
    }

    .hero-bg-image {
      background-image: linear-gradient(rgba(23, 31, 53, 0.7), rgba(14, 74, 165, 0.8)), url('img/escola-banner.jpg');
    }
  </style>
</head>

<body class="bg-white">

  <!-- Cabeçalho -->
  <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="container mx-auto">
      <div class="px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="bg-blue-600 rounded-lg w-10 h-10 flex-shrink-0">
            <img class="w-10 h-10 object-contain p-2" src="img/dove.png">
          </div>
          <div>
            <h1 class="text-xl font-regular text-gray-800">Colégio Esperança</h1>
            <p class="text-sm text-gray-500">Educando para o futuro</p>
          </div>
        </div>
        <!-- Navegação -->
        <nav class="hidden md:flex items-center gap-2">
          <a href="index.php" class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg">Home</a>
          <a href="noticias.php" class="px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Notícias</a>
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
        <a href="index.php" class="block text-center py-2 px-4 text-sm font-semibold text-white bg-gray-900 rounded-lg">Home</a>
        <a href="noticias.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Notícias</a>
        <a href="biblioteca.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 hover:bg-gray-100 rounded-lg">Biblioteca</a>
        <a href="login.php" class="block text-center mt-2 py-2 px-4 text-sm font-semibold text-gray-800 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Login</a>
      </div>
    </div>
  </header>

  <main>
    <!-- Seção Hero -->
    <section class="hero-bg-image bg-cover bg-center text-white">
      <div class="container mx-auto px-6 py-48 text-center">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-4xl md:text-6xl font-regular leading-tight">Bem-vindo ao Colégio Esperança</h1>
          <p class="mt-4 text-lg md:text-xl max-w-2xl mx-auto">Uma instituição comprometida com a excelência educacional e o desenvolvimento integral dos nossos estudantes.</p>
          <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="login.php" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">Área do Aluno</a>
            <a href="noticias.php" class="bg-white hover:bg-gray-200 text-blue-800 font-semibold py-3 px-6 rounded-lg transition duration-300">Ver Notícias</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Seção Sobre -->
    <section class="py-16 md:py-24 bg-white">
      <div class="container mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto">
          <h2 class="text-3xl md:text-4xl font-regular text-gray-900">Sobre Nossa Escola</h2>
          <p class="mt-4 text-lg text-gray-600">Há mais de 30 anos formando cidadãos conscientes e preparados para os desafios do futuro.</p>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-12">
          <!-- Card 1 -->
          <div class="bg-white p-6 rounded-xl border border-gray-200 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
              <img class="w-8 h-8 object-contain" src="img/alunos.svg">
            </div>
            <p class="text-2xl text-gray-900 mt-4">1.200+</p>
            <p class="text-gray-500">Alunos Ativos</p>
          </div>
          <!-- Card 2 -->
          <div class="bg-white p-6 rounded-xl border border-gray-200 text-center">
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto">
              <img class="w-8 h-8 object-contain" src="img/professores.svg">
            </div>
            <p class="text-2xl text-gray-900 mt-4">80+</p>
            <p class="text-gray-500">Professores Qualificados</p>
          </div>
          <!-- Card 3 -->
          <div class="bg-white p-6 rounded-xl border border-gray-200 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
              <img class="w-8 h-8 object-contain" src="img/livro.svg">
            </div>
            <p class="text-2xl text-gray-900 mt-4">15.000+</p>
            <p class="text-gray-500">Livros na Biblioteca</p>
          </div>
          <!-- Card 4 -->
          <div class="bg-white p-6 rounded-xl border border-gray-200 text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto">
              <img class="w-8 h-8 object-contain" src="img/enem.svg">
            </div>
            <p class="text-2xl text-gray-900 mt-4">95%</p>
            <p class="text-gray-500">Aprovação no ENEM</p>
          </div>
        </div>

        <!-- Missão, Visão e Valores -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-16">
          <!-- Missão -->
          <div class="bg-blue-50 p-8 rounded-xl">
            <h3 class="text-xl font-semibold text-blue-800">Nossa Missão</h3>
            <p class="mt-2 text-gray-600">Proporcionar uma educação de qualidade, desenvolvendo habilidades acadêmicas, sociais e emocionais em um ambiente acolhedor e estimulante.</p>
          </div>
          <!-- Visão -->
          <div class="bg-orange-50 p-8 rounded-xl">
            <h3 class="text-xl font-semibold text-orange-800">Nossa Visão</h3>
            <p class="mt-2 text-gray-600">Ser referência em educação, reconhecida pela excelência no ensino e pela formação de cidadãos éticos, críticos e preparados para o mundo.</p>
          </div>
          <!-- Valores -->
          <div class="bg-green-50 p-8 rounded-xl">
            <h3 class="text-xl font-semibold text-green-800">Nossos Valores</h3>
            <p class="mt-2 text-gray-600">Respeito, responsabilidade, inovação, colaboração e compromisso com a aprendizagem contínua e o desenvolvimento integral do aluno.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Seção de Informações -->
    <section class="py-16 md:py-24 bg-gray-50">
      <div class="container mx-auto px-6">
        <div class="bg-white p-8 rounded-xl border border-gray-200">
          <h3 class="text-xl font-semibold text-gray-900 mb-6">Informações</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Endereço -->
            <div class="flex gap-4">
              <div class="w-8 h-8 flex-shrink-0">
                <img class="w-6 h-6 object-contain" src="img/pin-map.png">
              </div>
              <div>
                <h4 class="font-bold text-gray-800">Endereço</h4>
                <p class="text-gray-600">Av. Educação, 1000 - Centro</p>
                <p class="text-gray-600">João Pessoa, PB - CEP 01000-000</p>
              </div>
            </div>
            <!-- Contato -->
            <div class="flex gap-4">
              <div class="w-8 h-8 flex-shrink-0">
                <img class="w-7 h-7 object-contain" src="img/telephone.png">
                </svg>
              </div>
              <div>
                <h4 class="font-bold text-gray-800">Contato</h4>
                <p class="text-gray-600">Telefone: (83) 8000-0000</p>
                <p class="text-gray-600">E-mail: esperanca@escola.com.br</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Rodapé -->
  <footer class="bg-gray-900 text-white">
    <div class="container mx-auto px-6 py-6 text-center text-sm">
      <p>Colégio Esperança © 2025. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script>
    // JavaScript (menu mobile)
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