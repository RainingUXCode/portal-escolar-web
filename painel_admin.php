<?php
require_once 'app/includes/funcoes.php';
require_once 'app/includes/conexao.php';

proteger_pagina(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['action']) && $_POST['action'] === 'criar_enquete') {
    $pergunta = trim($_POST['pergunta'] ?? '');
    $opcoes = array_filter(array_map('trim', $_POST['opcoes'] ?? []));

    if ($pergunta === '' || count($opcoes) < 2) {
      redirecionar('painel_admin.php', 'erro', 'Pergunta e pelo menos duas opções são obrigatórias.');
    }

    $stmt = $conn->prepare("INSERT INTO enquetes (pergunta) VALUES (?)");
    $stmt->bind_param("s", $pergunta);
    if (!$stmt->execute()) {
      $erro = $stmt->error;
      $stmt->close();
      redirecionar('painel_admin.php', 'erro', 'Falha ao criar enquete: ' . $erro);
    }

    $enquete_id = $stmt->insert_id;
    $stmt->close();

    $stmt_opcao = $conn->prepare("INSERT INTO opcoes_enquete (enquete_id, texto_opcao) VALUES (?, ?)");
    foreach ($opcoes as $texto_opcao) {
      $stmt_opcao->bind_param("is", $enquete_id, $texto_opcao);
      $stmt_opcao->execute();
    }
    $stmt_opcao->close();

    redirecionar('painel_admin.php', 'sucesso', 'Enquete criada com sucesso.');
  }

  if (!empty($_POST['action']) && $_POST['action'] === 'excluir_enquete' && !empty($_POST['enquete_id'])) {
    $enquete_id = (int) $_POST['enquete_id'];

    $stmt = $conn->prepare("DELETE FROM votos WHERE opcao_id IN (SELECT id FROM opcoes_enquete WHERE enquete_id = ?)");
    if ($stmt) {
      $stmt->bind_param("i", $enquete_id);
      $stmt->execute();
      $stmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM opcoes_enquete WHERE enquete_id = ?");
    $stmt->bind_param("i", $enquete_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM enquetes WHERE id = ?");
    $stmt->bind_param("i", $enquete_id);
    if ($stmt->execute()) {
      $stmt->close();
      redirecionar('painel_admin.php', 'sucesso', 'Enquete excluída com sucesso.');
    }

    $erro = $stmt->error;
    $stmt->close();
    redirecionar('painel_admin.php', 'erro', 'Falha ao excluir enquete: ' . $erro);
  }

  if (!empty($_POST['action']) && $_POST['action'] === 'criar_noticia') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $imagem_url = '';

    if ($titulo === '' || $autor === '' || $conteudo === '') {
      redirecionar('painel_admin.php', 'erro', 'Título, autor e conteúdo são obrigatórios.');
    }

    $uploadDir = __DIR__ . '/assets/img/noticias';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
      if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
        redirecionar('painel_admin.php', 'erro', 'Falha no upload da imagem.');
      }

      $allowedTypes = ['image/jpeg' => '.jpg', 'image/png' => '.png', 'image/gif' => '.gif'];
      $fileType = mime_content_type($_FILES['imagem']['tmp_name']);

      if (!array_key_exists($fileType, $allowedTypes)) {
        redirecionar('painel_admin.php', 'erro', 'Tipo de imagem não suportado. Use JPG, PNG ou GIF.');
      }

      $fileSize = $_FILES['imagem']['size'];
      if ($fileSize > 5 * 1024 * 1024) {
        redirecionar('painel_admin.php', 'erro', 'A imagem deve ter no máximo 5MB.');
      }

      $extension = $allowedTypes[$fileType];
      $fileName = time() . '_' . bin2hex(random_bytes(6)) . $extension;
      $destination = $uploadDir . '/' . $fileName;

      if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $destination)) {
        redirecionar('painel_admin.php', 'erro', 'Erro ao salvar a imagem enviada.');
      }

      $imagem_url = 'assets/img/noticias/' . $fileName;
    }

    $stmt = $conn->prepare("INSERT INTO noticias (titulo, conteudo, autor, imagem_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $conteudo, $autor, $imagem_url);

    if ($stmt->execute()) {
      $stmt->close();
      redirecionar('painel_admin.php', 'sucesso', 'Notícia criada com sucesso.');
    }

    $erro = $stmt->error;
    $stmt->close();
    redirecionar('painel_admin.php', 'erro', 'Falha ao criar notícia: ' . $erro);
  }

  if (!empty($_POST['action']) && $_POST['action'] === 'excluir_noticia' && !empty($_POST['noticia_id'])) {
    $noticia_id = (int) $_POST['noticia_id'];
    $stmt = $conn->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $noticia_id);

    if ($stmt->execute()) {
      $stmt->close();
      redirecionar('painel_admin.php', 'sucesso', 'Notícia excluída com sucesso.');
    }

    $erro = $stmt->error;
    $stmt->close();
    redirecionar('painel_admin.php', 'erro', 'Falha ao excluir notícia: ' . $erro);
  }
}

$sql_alunos = "SELECT id, nome, email, matricula, data_nascimento, endereco FROM usuarios WHERE nivel_acesso = 'aluno' AND NOT (email LIKE 'perf%@escola.com' OR email LIKE 'test_%@escola.com' OR email LIKE 'teste%@escola.com' OR nome LIKE '%Teste%' OR nome LIKE 'Perf Test%') ORDER BY nome ASC";
$result_alunos = $conn->query($sql_alunos);

// CONTAGEM TOTAL DE ALUNOS
$sql_count = "SELECT COUNT(*) AS total_alunos FROM usuarios WHERE nivel_acesso = 'aluno' AND NOT (email LIKE 'perf%@escola.com' OR email LIKE 'test_%@escola.com' OR email LIKE 'teste%@escola.com' OR nome LIKE '%Teste%' OR nome LIKE 'Perf Test%')";
$result_count = $conn->query($sql_count);
$total_alunos = $result_count->fetch_assoc()['total_alunos'];

$sql_noticias = "SELECT id, titulo, autor, conteudo, imagem_url, data_publicacao FROM noticias ORDER BY data_publicacao DESC";
$result_noticias = $conn->query($sql_noticias);
$total_noticias = $result_noticias ? $result_noticias->num_rows : 0;

$sql_enquetes = "SELECT id, pergunta FROM enquetes ORDER BY id DESC";
$result_enquetes = $conn->query($sql_enquetes);
$total_enquetes = $result_enquetes ? $result_enquetes->num_rows : 0;

$mensagem_alunos = $total_alunos . ($total_alunos == 1 ? " Aluno" : " Alunos");
$tem_alunos = $total_alunos > 0;
?>

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
      <img class="w-4 h-4" src="assets/img/voltar.svg">
      Voltar
    </a>

    <!-- Card Principal do Admin -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-8 w-full">

      <!-- Cabeçalho do Card (Avatar e Título) -->
      <div class="flex flex-row items-center gap-4 mb-6">
        <!-- Avatar -->
        <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
          <img src="assets/perfil/Adm.png" alt="Avatar Admin" class="w-full h-full object-cover rounded-full">
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
          <img class="w-4 h-4" src="assets/img/noticia.svg">
          Notícias
        </button>
        <!-- Aba Enquetes -->
        <button data-tab-toggle="enquetes" class="tab-button flex-1 text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md py-2 px-4 text-center inline-flex items-center justify-center gap-2">
          <img class="w-4 h-4" src="assets/img/enquete-black.svg">
          Enquetes
        </button>
        <!-- Aba Alunos -->
        <button data-tab-toggle="alunos" class="tab-button flex-1 text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-md py-2 px-4 text-center inline-flex items-center justify-center gap-2">
          <img class="w-4 h-4" src="assets/img/alunos-black.svg">
          Alunos
        </button>
      </nav>

      <!-- Conteúdo da Aba "Notícias" -->
      <div id="tab-noticias" class="tab-content">

        <!-- Seção: Criar Nova Notícia -->
        <section>
          <h2 class="text-xl font-semibold text-gray-900">Criar Nova Notícia</h2>
          <p class="text-sm text-gray-500 mb-4">Publique notícias e eventos para os estudantes</p>
          <?php echo exibir_flash_message(); ?>

          <form id="form-criar-noticia" class="space-y-4" method="post" action="painel_admin.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="criar_noticia">
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
              <label for="imagem" class="block text-sm font-medium text-gray-700 mb-1">Imagem da Notícia:</label>
              <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/gif" class="w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 p-2">
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
            <span class="text-sm text-gray-500"><?php echo $total_noticias; ?> <?php echo $total_noticias === 1 ? 'notícia' : 'notícias'; ?></span>
          </div>

          <div id="noticias-existentes-container" class="space-y-4">
            <?php if ($total_noticias > 0): ?>
              <?php while ($noticia = $result_noticias->fetch_assoc()): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
                  <div>
                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($noticia['titulo']); ?></h4>
                    <p class="text-sm text-gray-600 line-clamp-2 max-w-prose"><?php echo nl2br(htmlspecialchars(mb_strlen($noticia['conteudo']) > 220 ? mb_substr($noticia['conteudo'], 0, 220) . '...' : $noticia['conteudo'])); ?></p>
                    <div class="text-xs text-gray-500 mt-1">
                      <span>Por: <?php echo htmlspecialchars($noticia['autor']); ?></span> • <span><?php echo date('d/m/Y', strtotime($noticia['data_publicacao'])); ?></span>
                    </div>
                  </div>
                  <form method="post" action="painel_admin.php" class="flex-shrink-0 ml-4">
                    <input type="hidden" name="action" value="excluir_noticia">
                    <input type="hidden" name="noticia_id" value="<?php echo (int) $noticia['id']; ?>">
                    <button type="submit" class="delete-noticia-btn bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg transition-colors">
                      <img class="w-5 h-5" src="assets/img/lixo.svg" alt="Excluir">
                    </button>
                  </form>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-600">
                Nenhuma notícia cadastrada no sistema.
              </div>
            <?php endif; ?>
          </div>
        </section>
      </div>

      <!-- Conteúdo da Aba "Enquetes" -->
      <div id="tab-enquetes" class="tab-content hidden">
        <!-- Seção: Criar Nova Enquete -->
        <section>
          <h2 class="text-xl font-semibold text-gray-900">Criar Nova Enquete</h2>
          <p class="text-sm text-gray-500 mb-4">Crie enquetes para coletar a opinião dos estudantes</p>
          <?php echo exibir_flash_message(); ?>

          <form id="form-criar-enquete" class="space-y-4" method="post" action="painel_admin.php">
            <input type="hidden" name="action" value="criar_enquete">
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
              <img class="w-3 h-3" src="assets/img/mais.png">
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
            <span class="text-sm text-gray-500"><?php echo $total_enquetes; ?> <?php echo $total_enquetes === 1 ? 'enquete' : 'enquetes'; ?></span>
          </div>

          <div id="enquetes-existentes-container" class="space-y-4">
            <?php if ($total_enquetes > 0): ?>
              <?php while ($enquete = $result_enquetes->fetch_assoc()): ?>
                <?php
                $stmt_opcoes = $conn->prepare("SELECT o.id, o.texto_opcao, COUNT(v.id) AS votos FROM opcoes_enquete o LEFT JOIN votos v ON o.id = v.opcao_id WHERE o.enquete_id = ? GROUP BY o.id, o.texto_opcao ORDER BY o.id ASC");
                $stmt_opcoes->bind_param("i", $enquete['id']);
                $stmt_opcoes->execute();
                $result_opcoes = $stmt_opcoes->get_result();
                $total_votos = 0;
                ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
                  <div>
                    <h4 class="font-semibold text-gray-800"><?php echo htmlspecialchars($enquete['pergunta']); ?></h4>
                    <ul class="text-sm text-gray-600 list-disc list-inside mt-1 space-y-1">
                      <?php while ($opcao = $result_opcoes->fetch_assoc()): ?>
                        <?php $total_votos += (int) $opcao['votos']; ?>
                        <li><?php echo htmlspecialchars($opcao['texto_opcao']); ?> (<?php echo (int) $opcao['votos']; ?>)</li>
                      <?php endwhile; ?>
                    </ul>
                    <div class="text-xs text-gray-500 mt-2">Total: <?php echo $total_votos; ?> votos</div>
                  </div>
                  <form method="post" action="painel_admin.php" class="flex-shrink-0 ml-4">
                    <input type="hidden" name="action" value="excluir_enquete">
                    <input type="hidden" name="enquete_id" value="<?php echo (int) $enquete['id']; ?>">
                    <button type="submit" class="bg-red-500 text-red-700 hover:bg-red-400 p-2 rounded-lg transition-colors">
                      <img class="w-5 h-5" src="assets/img/lixo.svg" alt="Excluir">
                    </button>
                  </form>
                </div>
                <?php $stmt_opcoes->close(); ?>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-600">
                Nenhuma enquete cadastrada no sistema.
              </div>
            <?php endif; ?>
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

    });
  </script>

</body>

</html>