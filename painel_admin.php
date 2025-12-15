<?php
require_once 'includes/funcoes.php';
require_once 'includes/conexao.php';

proteger_pagina(['admin']);

$sql_alunos = "SELECT id, nome, email, matricula, data_nascimento, endereco FROM usuarios WHERE nivel_acesso = 'aluno' ORDER BY nome ASC";
$result_alunos = $conn->query($sql_alunos);

// CONTAGEM TOTAL DE ALUNOS
$sql_count = "SELECT COUNT(*) AS total_alunos FROM usuarios WHERE nivel_acesso = 'aluno'";
$result_count = $conn->query($sql_count);
$total_alunos = $result_count->fetch_assoc()['total_alunos'];

$mensagem_alunos = $total_alunos . ($total_alunos == 1 ? " Aluno" : " Alunos");
$tem_alunos = $total_alunos > 0;
?>

<!DOCTYPE html>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Admin - Colégio Esperança</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(240, 245, 255, 0.7) 70%, rgba(255, 247, 240, 0.7));
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    textarea::placeholder,
    input::placeholder {
      color: #A1A1AA;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4 py-8 bg-gray-50">

  <div class="w-full max-w-5xl">
    <!-- Botão Voltar -->
    <a href="area_admin.php" class="inline-flex items-center gap-3 text-sm font-semibold text-gray-700 hover:text-gray-900 mb-4 bg-white backdrop-blur-sm px-3 py-2 rounded-lg border border-gray-200">
      <img class="w-4 h-4" src="img/voltar.svg">
      Voltar
    </a>

    <!-- Card Principal do Admin -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-8 w-full">

      <!-- Cabeçalho do Card (Avatar e Título) -->
      <div class="flex flex-row items-center gap-4 mb-6">
        <!-- Avatar -->
        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
          <img src="perfil/Adm.png" alt="Avatar Admin" class="w-full h-full object-cover rounded-full">
        </div>
        <!-- Título e Subtítulo -->
        <div>
          <h2 class="text-2xl font-semibold text-gray-900">Painel do Administrador</h2>
          <p class="text-gray-500 mt-1">Gerencie notícias, enquetes e visualize os alunos</p>
        </div>
      </div>

      <!-- Navegação em Abas (Tabs) -->
      <nav class="flex space-x-2 bg-gray-100 p-1.5 rounded-lg mb-6">
        <!-- Aba Notícias -->
        <button data-tab-toggle="noticias" class="tab-button flex-1 text-sm font-semibold text-gray-900 bg-white shadow-sm rounded-md py-2 px-4 text-center inline-flex items-center justify-center gap-2">
          <img class="w-4 h-4" src="img/noticia.svg">
          Notícias
        </button>
        <!-- Aba Enquetes -->
        <button data-tab-toggle="enquetes" class="tab-button flex-1 text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md py-2 px-4 text-center inline-flex items-center justify-center gap-2">
          <img class="w-4 h-4" src="img/enquete-black.svg">
          Enquetes
        </button>
        <!-- Aba Alunos -->
        <button data-tab-toggle="alunos" class="tab-button flex-1 text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md py-2 px-4 text-center inline-flex items-center justify-center gap-2">
          <img class="w-4 h-4" src="img/alunos-black.svg">
          Alunos
        </button>
      </nav>

      <!-- Conteúdo da Aba "Notícias" -->
      <div id="tab-noticias" class="tab-content">

        <!-- Seção: Criar Nova Notícia -->
        <section>
          <h2 class="text-xl font-semibold text-gray-900">Criar Nova Notícia</h2>
          <p class="text-sm text-gray-500 mb-4">Publique notícias e eventos para os estudantes</p>

          <form id="form-criar-noticia" class="space-y-4">
            <div>
              <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título:</label>
              <input type="text" id="titulo" name="titulo" placeholder="Título da notícia" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
              <label for="noticia-autor" class="block text-sm font-medium text-gray-700 mb-1">Autor:</label>
              <input type="text" id="noticia-autor" name="autor" placeholder="Nome do autor (Ex: Coordenação, Prof. João)" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
              <label for="conteudo" class="block text-sm font-medium text-gray-700 mb-1">Conteúdo:</label>
              <textarea id="conteudo" name="conteudo" rows="4" placeholder="Conteúdo da notícia..." required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div>
              <label for="imagem_url" class="block text-sm font-medium text-gray-700 mb-1">URL da Imagem:</label>
              <input type="text" id="imagem_url" name="imagem_url" placeholder="https://..." class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-gray-900 text-white py-2 px-5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition duration-300">
              Criar Novo
            </button>
          </form>
        </section>

        <!-- Seção: Notícias Existentes -->
        <section class="mt-8 pt-6 border-t border-gray-200">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Notícias Existentes</h3>
            <span class="text-sm text-gray-500">4 notícias</span>
          </div>

          <div id="noticias-existentes-container" class="space-y-4">
            <!-- Item Notícia 1 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Feira de Ciências 2025 - Inscrições Abertas</h4>
                <p class="text-sm text-gray-600 line-clamp-2 max-w-prose">As inscrições para a Feira de Ciências estão abertas! Este ano o tema é por votação na área do aluno. Vote e participe com seu projeto inovador até 02 de Dezembro.</p>
                <div class="text-xs text-gray-500 mt-1">
                  <span>Por: Coordenação Esperança</span> • <span>25/11/2025</span>
                </div>
              </div>
              <button type="button" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4 transition-colors">
                <img class="w-5 h-5" src="img/lixo.svg" alt="Excluir">
              </button>
            </div>

            <!-- Item Notícia 2 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Bem-vindos ao Novo Ano Letivo!</h4>
                <p class="text-sm text-gray-600 line-clamp-2 max-w-prose">Estamos felizes em receber todos os alunos para mais um ano de aprendizado e crescimento. Que este ano seja repleto de conquistas e novas amizades!</p>
                <div class="text-xs text-gray-500 mt-1">
                  <span>Por: Coordenação Esperança</span> • <span>14/02/2025</span>
                </div>
              </div>
              <button type="button" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4 transition-colors">
                <img class="w-5 h-5" src="img/lixo.svg" alt="Excluir">
              </button>
            </div>

            <!-- Item Notícia 3 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Torneio Esportivo Inter-Classes</h4>
                <p class="text-sm text-gray-600 line-clamp-2 max-w-prose">Prepare-se para o nosso torneio anual! Futebol, vôlei, basquete e muito mais. Inscrições com o professor de Educação Física.</p>
                <div class="text-xs text-gray-500 mt-1">
                  <span>Por: Professor Allyson</span> • <span>13/10/2025</span>
                </div>
              </div>
              <button type="button" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4 transition-colors">
                <img class="w-5 h-5" src="img/lixo.svg" alt="Excluir">
              </button>
            </div>

            <!-- Item Notícia 4 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Dicas de Estudo: Como se Preparar para as Provas</h4>
                <p class="text-sm text-gray-600 line-clamp-2 max-w-prose">Organize seu tempo, faça resumos, pratique exercícios e não deixe para última hora! Lembre-se: a biblioteca está aberta...</p>
                <div class="text-xs text-gray-500 mt-1">
                  <span>Por: Professora Carolina</span> • <span>09/09/2025</span>
                </div>
              </div>
              <button type="button" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4 transition-colors">
                <img class="w-5 h-5" src="img/lixo.svg" alt="Excluir">
              </button>
            </div>

          </div>
        </section>
      </div>

      <!-- Conteúdo da Aba "Enquetes" -->
      <div id="tab-enquetes" class="tab-content hidden">
        <!-- Seção: Criar Nova Enquete -->
        <section>
          <h2 class="text-xl font-semibold text-gray-900">Criar Nova Enquete</h2>
          <p class="text-sm text-gray-500 mb-4">Crie enquetes para coletar a opinião dos estudantes</p>

          <form id="form-criar-enquete" class="space-y-4">
            <div>
              <label for="pergunta" class="block text-sm font-medium text-gray-700 mb-1">Pergunta:</label>
              <input type="text" id="pergunta" name="pergunta" placeholder="Ex: Quem deve ser o representante da turma?" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Opções -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Opções:</label>
              <div id="opcoes-container" class="space-y-2">
                <input type="text" name="opcoes[]" placeholder="Opção 1" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="opcoes[]" placeholder="Opção 2" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
              </div>
            </div>

            <!-- Botão Adicionar Opção -->
            <button type="button" id="add-opcao-btn" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
              <img class="w-3 h-3" src="img/mais.png">
              Adicionar Opção
            </button>

            <button type="submit" class="bg-gray-900 text-white py-2 px-5 rounded-lg text-sm font-semibold hover:bg-gray-800 transition duration-300">
              Criar Novo
            </button>
          </form>
        </section>

        <!-- Seção: Enquetes Existentes -->
        <section class="mt-8 pt-6 border-t border-gray-200">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Enquetes Existentes</h3>
            <span class="text-sm text-gray-500">3 enquetes</span>
          </div>

          <div id="enquetes-existentes-container" class="space-y-4">
            <!-- Item Enquete 1 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Cardápio de sexta-feira - Qual sua preferência?</h4>
                <ul class="text-sm text-gray-600 list-disc list-inside mt-1 space-y-1">
                  <li>Pizza (0)</li>
                  <li>Hambúrguer (0)</li>
                  <li>Macarronada (0)</li>
                  <li>Comida Brasileira (0)</li>
                </ul>
                <div class="text-xs text-gray-500 mt-2">Total: 0 votos</div>
              </div>
              <button class="delete-enquete-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4">
                <img class="w-5 h-5" src="img/lixo.svg">
              </button>
            </div>

            <!-- Item Enquete 2 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Qual tema preferem para a Feira de Ciências?</h4>
                <ul class="text-sm text-gray-600 list-disc list-inside mt-1 space-y-1">
                  <li>Energias Renováveis (0)</li>
                  <li>Robótica e Automação (0)</li>
                  <li>Biotecnologia (0)</li>
                  <li>Astronomia (0)</li>
                </ul>
                <div class="text-xs text-gray-500 mt-2">Total: 0 votos</div>
              </div>
              <button class="delete-enquete-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4">
                <img class="w-5 h-5" src="img/lixo.svg">
              </button>
            </div>

            <!-- Item Enquete 3 -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
              <div>
                <h4 class="font-semibold text-gray-800">Qual atividade extracurricular você gostaria que a escola oferecesse?</h4>
                <ul class="text-sm text-gray-600 list-disc list-inside mt-1 space-y-1">
                  <li>Teatro e Artes Cênicas (0)</li>
                  <li>Clube de Xadrez (0)</li>
                  <li>Aulas de Música (0)</li>
                  <li>Programação e Robótica (0)</li>
                </ul>
                <div class="text-xs text-gray-500 mt-2">Total: 0 votos</div>
              </div>
              <button class="delete-enquete-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4">
                <img class="w-5 h-5" src="img/lixo.svg">
              </button>
            </div>
          </div>
        </section>
      </div>

      <!-- Conteúdo da Aba "Alunos" -->
      <div id="tab-alunos" class="tab-content hidden">
        <section>
          <div class="flex justify-between items-center mb-4">
            <div>
              <h2 class="text-xl font-semibold text-gray-900">Alunos Cadastrados</h2>
              <p class="text-sm text-gray-500">Lista completa de estudantes registrados no sistema</p>
            </div>
            <span class="bg-gray-100 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg">
              <?php echo $mensagem_alunos; ?>
            </span>
          </div>

          <!-- Tabela de Alunos -->
          <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nome</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Matrícula</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Data de Nascimento</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Endereço</th>

              </thead>
              <tbody class="bg-white divide-y divide-gray-200">

                <?php if (isset($tem_alunos) && $tem_alunos): ?>
                  <?php while ($aluno = $result_alunos->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">

                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?php echo htmlspecialchars($aluno['nome']); ?>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($aluno['email']); ?>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($aluno['matricula']); ?>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($aluno['data_nascimento']); ?>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo htmlspecialchars($aluno['endereco']); ?>
                      </td>

                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      Nenhum aluno cadastrado no sistema.
                    </td>
                  </tr>
                <?php endif; ?>

              </tbody>
            </table>
          </div>
        </section>
      </div>

    </div>
  </div>

  <!-- JavaScript para Abas e Botão Adicionar Opção -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // --- Lógica das Abas ---
      const tabButtons = document.querySelectorAll('.tab-button');
      const tabContents = document.querySelectorAll('.tab-content');

      tabButtons.forEach(button => {
        button.addEventListener('click', () => {
          const targetTab = button.getAttribute('data-tab-toggle');
          tabContents.forEach(content => content.classList.add('hidden'));
          tabButtons.forEach(btn => {
            btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
            btn.classList.add('text-gray-600', 'hover:bg-gray-200');
          });
          const activeContent = document.getElementById(`tab-${targetTab}`);
          if (activeContent) {
            activeContent.classList.remove('hidden');
          }
          button.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
          button.classList.remove('text-gray-600', 'hover:bg-gray-200');
        });
      });

      // --- Lógica de Adicionar Opção (Enquete) ---
      const addOpcaoBtn = document.getElementById('add-opcao-btn');
      const opcoesContainer = document.getElementById('opcoes-container');

      let optionCount = 2;

      if (addOpcaoBtn && opcoesContainer) {
        addOpcaoBtn.addEventListener('click', () => {
          optionCount++;
          const newOptionInput = document.createElement('input');
          newOptionInput.type = 'text';
          newOptionInput.name = 'opcoes[]';
          newOptionInput.placeholder = `Opção ${optionCount}`;
          newOptionInput.required = true;
          newOptionInput.className = 'w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500';
          opcoesContainer.appendChild(newOptionInput);
        });
      }

      // --- Lógica para Criar Notícia ---
      const formCriarNoticia = document.getElementById('form-criar-noticia');
      const noticiasContainer = document.getElementById('noticias-existentes-container');

      if (formCriarNoticia && noticiasContainer) {
        formCriarNoticia.addEventListener('submit', (e) => {
          e.preventDefault();
          const titulo = document.getElementById('titulo').value;
          const autor = document.getElementById('noticia-autor').value;
          const conteudo = document.getElementById('conteudo').value;
          const dataAtual = new Date().toLocaleDateString('pt-BR');
          const novaNoticiaHTML = `
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
            <div>
              <h4 class="font-semibold text-gray-800">${titulo}</h4>
              <p class="text-sm text-gray-600 line-clamp-2 max-w-prose">${conteudo}</p>
              <div class="text-xs text-gray-500 mt-1">
                <span>Por: ${autor}</span> • <span>${dataAtual}</span>
              </div>
            </div>
            <button type="button" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4 transition-colors">
              <img class="w-5 h-5" src="/img/lixo.svg" alt="Excluir">
            </button>
          </div>`;
          noticiasContainer.insertAdjacentHTML('afterbegin', novaNoticiaHTML);
          formCriarNoticia.reset();
        });
      }

      // --- Lógica para Excluir Notícia ---
      if (noticiasContainer) {
        noticiasContainer.addEventListener('click', (e) => {
          const deleteButton = e.target.closest('.delete-noticia-btn');
          if (deleteButton) {
            const noticiaCard = deleteButton.closest('.bg-gray-50');
            if (noticiaCard) {
              noticiaCard.remove();
            }
          }
        });
      }

      // --- Lógica de Enquetes ---

      const formCriarEnquete = document.getElementById('form-criar-enquete');
      const enquetesContainer = document.getElementById('enquetes-existentes-container');

      const renderizarNovaEnquete = (pergunta, opcoesArray) => {
        const opcoesHTML = opcoesArray.map(texto => `
        <li>${texto} (0 votos)</li>
      `).join('');

        const enqueteHTML = `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
          <div>
            <h4 class="font-semibold text-gray-800">${pergunta}</h4>
            <ul class="text-sm text-gray-600 list-disc list-inside mt-1 space-y-1">
              ${opcoesHTML}
            </ul>
            <div class="text-xs text-gray-500 mt-2">Total: 0 votos</div>
          </div>
          <button class="delete-enquete-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg flex-shrink-0 ml-4">
            <img class="w-5 h-5" src="/img/lixo.svg">
          </button>
        </div>
      `;
        enquetesContainer.insertAdjacentHTML('afterbegin', enqueteHTML);
      };

      if (formCriarEnquete && enquetesContainer) {
        formCriarEnquete.addEventListener('submit', (e) => {
          e.preventDefault();


          const pergunta = document.getElementById('pergunta').value;
          const inputsOpcoes = opcoesContainer.querySelectorAll('input[name="opcoes[]"]');

          const opcoes = Array.from(inputsOpcoes)
            .map(input => input.value.trim())
            .filter(value => value);

          if (pergunta && opcoes.length >= 2) {
            renderizarNovaEnquete(pergunta, opcoes);
            formCriarEnquete.reset();
            opcoesContainer.innerHTML = `
            <input type="text" name="opcoes[]" placeholder="Opção 1" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="opcoes[]" placeholder="Opção 2" required class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
          `;
            optionCount = 2;
          }
        });
      }

      if (enquetesContainer) {
        enquetesContainer.addEventListener('click', (e) => {
          const deleteButton = e.target.closest('.delete-enquete-btn');

          if (deleteButton) {
            const enqueteCard = deleteButton.closest('.bg-gray-50');
            if (enqueteCard) {
              enqueteCard.remove();
            }
          }
        });
      }

    });
  </script>

</body>

</html>