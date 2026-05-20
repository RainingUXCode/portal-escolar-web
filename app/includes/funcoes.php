<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


function redirecionar($url, $tipo, $mensagem)
{
  $_SESSION[$tipo] = $mensagem;

  header("Location: $url");
  exit();
}


function is_logged_in()
{
  return isset($_SESSION['usuario_id']);
}


function proteger_pagina($permissoes = [])
{
  if (!is_logged_in()) {
    redirecionar('login.php', 'erro', 'Você precisa fazer login para acessar esta página.');
  }

  if (!empty($permissoes) && !in_array($_SESSION['usuario_tipo'], $permissoes)) {
    redirecionar('index.php', 'erro', 'Você não tem permissão para acessar esta área.');
  }
}

function exibir_flash_message()
{
  $mensagem = '';
  $classes = [
    'sucesso' => 'bg-green-100 border-green-400 text-green-700',
    'erro' => 'bg-red-100 border-red-400 text-red-700',
  ];

  foreach ($classes as $tipo => $classe) {
    if (isset($_SESSION[$tipo])) {
      $mensagem = '<div class="' . $classe . ' border px-4 py-3 rounded relative mb-4" role="alert">' . $_SESSION[$tipo] . '</div>';
      unset($_SESSION[$tipo]);
      return $mensagem;
    }
  }
  return '';
}

function calcular_total_votos($conn, $enquete_id)
{
  $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM votos WHERE enquete_id = ?");
  $stmt->bind_param("i", $enquete_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $total = $result->fetch_assoc()['total'] ?? 0;
  $stmt->close();
  return (int)$total;
}


function checar_voto_usuario($conn, $enquete_id, $usuario_id)
{
  $stmt = $conn->prepare("SELECT opcao_id FROM votos WHERE enquete_id = ? AND usuario_id = ?");
  $stmt->bind_param("ii", $enquete_id, $usuario_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $voto = $result->fetch_assoc()['opcao_id'];
    $stmt->close();
    return (int)$voto;
  }
  $stmt->close();
  return null; // Não votou
}


function obter_resultados_enquete($conn, $enquete_id)
{
  $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM votos WHERE enquete_id = ?");
  $stmt_total->bind_param("i", $enquete_id);
  $stmt_total->execute();
  $result_total = $stmt_total->get_result();
  $total_votos = $result_total->fetch_assoc()['total'] ?? 0;
  $stmt_total->close();


  $sql_resultados = "
        SELECT 
            o.id AS opcao_id, 
            o.texto_opcao, 
            COUNT(v.id) AS contagem
        FROM 
            opcoes_enquete o
        LEFT JOIN 
            votos v ON o.id = v.opcao_id
        WHERE 
            o.enquete_id = ?
        GROUP BY 
            o.id, o.texto_opcao
        ORDER BY 
            o.id
    ";

  $stmt_resultados = $conn->prepare($sql_resultados);
  $stmt_resultados->bind_param("i", $enquete_id);
  $stmt_resultados->execute();
  $result_resultados = $stmt_resultados->get_result();

  $opcoes_com_resultados = [];

  while ($row = $result_resultados->fetch_assoc()) {
    $contagem = (int)$row['contagem'];
    $porcentagem = $total_votos > 0 ? round(($contagem / $total_votos) * 100) : 0;

    $opcoes_com_resultados[] = [
      'texto'       => htmlspecialchars($row['texto_opcao']),
      'contagem'    => $contagem,
      'porcentagem' => $porcentagem
    ];
  }
  $stmt_resultados->close();

  return [
    'total_votos' => (int)$total_votos,
    'resultados'  => $opcoes_com_resultados
  ];
}
function obter_noticias_fixas()
{
  return [
    [
      'titulo' => 'Feira de Ciências 2025 - Inscrições Abertas',
      'autor' => 'Coordenação Esperança',
      'data' => '25 de Novembro de 2025',
      'conteudo' => 'As inscrições para a Feira de Ciências estão abertas! Este ano o tema é por votação na área do aluno. Vote e participe com seu projeto inovador até 02 de Dezembro.',
      'imagem_url' => 'assets/img/ciencias.jpg',
    ],
    [
      'titulo' => 'Torneio Esportivo Inter-Classes',
      'autor' => 'Professor Allyson',
      'data' => '13 de Outubro de 2025',
      'conteudo' => 'Prepare-se para o nosso torneio anual! Futebol, vôlei, basquete e muito mais. Inscrições com o professor de Educação Física.',
      'imagem_url' => 'assets/img/esporte.jpg',
    ],
    [
      'titulo' => 'Dicas de Estudo: Como se preparar para as Provas',
      'autor' => 'Professora Carolina',
      'data' => '09 de Setembro de 2025',
      'conteudo' => 'Organize seu tempo, faça resumos, pratique exercícios e não deixe para última hora! Lembre-se: a biblioteca está aberta de segunda a sexta, das 8h às 18h.',
      'imagem_url' => 'assets/img/estudos.jpg',
    ],
  ];
}

function render_noticias_cards(array $noticias)
{
  $html = '';

  foreach ($noticias as $noticia) {
    $imagem_url = !empty($noticia['imagem_url']) ? htmlspecialchars($noticia['imagem_url']) : '';
    $titulo = htmlspecialchars($noticia['titulo']);
    $autor = htmlspecialchars($noticia['autor']);
    $data = htmlspecialchars($noticia['data'] ?? '');
    $conteudo = htmlspecialchars($noticia['conteudo']);

    $html .= '<div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">';
    if ($imagem_url !== '') {
      $html .= '<img class="w-full h-80 object-cover" src="' . $imagem_url . '" alt="' . $titulo . '">';
    } else {
      $html .= '<div class="w-full h-80 bg-gray-100 flex items-center justify-center text-gray-500">Sem imagem</div>';
    }
    $html .= '<div class="p-6 flex flex-col flex-grow">';
    $html .= '<h3 class="text-xl text-gray-900">' . $titulo . '</h3>';
    $html .= '<div class="flex gap-4 text-sm text-gray-500 mt-2">';
    $html .= '<span class="flex items-center gap-1.5">';
    $html .= '<img class="w-4 h-4" src="assets/img/calendar.svg">';
    $html .= $data;
    $html .= '</span>';
    $html .= '<span class="flex items-center gap-1.5">';
    $html .= '<img class="w-4 h-4" src="assets/img/pessoa.svg">';
    $html .= $autor;
    $html .= '</span>';
    $html .= '</div>';
    $html .= '<p class="mt-4 text-gray-600 flex-grow">' . $conteudo . '</p>';
    $html .= '</div>';
    $html .= '</div>';
  }

  return $html;
}

function render_noticias_fixas_cards(array $noticias)
{
  return render_noticias_cards($noticias);
}
