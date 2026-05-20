<?php

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3307';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db = getenv('DB_NAME') ?: 'projeto';
$mysqliHost = $host;
if ($port !== '') {
    $mysqliHost .= ':' . $port;
}
$conn = new mysqli($mysqliHost, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

$create_noticias_table = "CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    autor VARCHAR(150) NOT NULL,
    imagem_url VARCHAR(255) DEFAULT NULL,
    data_publicacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($create_noticias_table);

$create_enquetes_table = "CREATE TABLE IF NOT EXISTS enquetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pergunta VARCHAR(255) NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    destaque TINYINT(1) DEFAULT 0,
    ativa TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($create_enquetes_table);

// Adicionar colunas se não existirem (para compatibilidade com enquetes existentes)
$columnCheck = $conn->query("SHOW COLUMNS FROM enquetes LIKE 'destaque'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE enquetes ADD COLUMN destaque TINYINT(1) DEFAULT 0");
}
$columnCheck = $conn->query("SHOW COLUMNS FROM enquetes LIKE 'ativa'");
if ($columnCheck && $columnCheck->num_rows === 0) {
    $conn->query("ALTER TABLE enquetes ADD COLUMN ativa TINYINT(1) DEFAULT 1");
}

$create_opcoes_enquete_table = "CREATE TABLE IF NOT EXISTS opcoes_enquete (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enquete_id INT NOT NULL,
    texto_opcao VARCHAR(255) NOT NULL,
    FOREIGN KEY (enquete_id) REFERENCES enquetes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$conn->query($create_opcoes_enquete_table);
