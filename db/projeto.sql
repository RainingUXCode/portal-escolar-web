-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Tempo de geração: 20/05/2026 às 08:39
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `enquetes`
--

CREATE TABLE `enquetes` (
  `id` int(10) NOT NULL,
  `pergunta` varchar(255) NOT NULL,
  `ativa` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `destaque` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `conteudo` text NOT NULL,
  `autor` varchar(150) NOT NULL,
  `imagem_url` varchar(255) DEFAULT NULL,
  `data_publicacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `opcoes_enquete`
--

CREATE TABLE `opcoes_enquete` (
  `id` int(10) NOT NULL,
  `enquete_id` int(10) NOT NULL,
  `texto_opcao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `opcoes_enquete`
--

INSERT INTO `opcoes_enquete` (`id`, `enquete_id`, `texto_opcao`) VALUES
(1, 1, 'Pizza'),
(2, 1, 'Hambúrguer'),
(3, 1, 'Macarronada'),
(4, 1, 'Comida Brasileira'),
(5, 2, 'Energias Renováveis'),
(6, 2, 'Robótica e Automação'),
(7, 2, 'Biotecnologia'),
(8, 2, 'Astronomia'),
(9, 3, 'Teatro'),
(10, 3, 'Clube de Xadrez'),
(11, 3, 'Aulas de Música'),
(12, 3, 'Aulas de Robótica'),
(13, 4, 'Maria Letícia'),
(14, 4, 'Felipe'),
(15, 4, 'Olivia'),
(16, 4, 'Pedro Henrique');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(70) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `matricula` varchar(15) NOT NULL,
  `data_nascimento` date NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `nivel_acesso` varchar(10) NOT NULL DEFAULT 'aluno'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `votos`
--

CREATE TABLE `votos` (
  `id` int(10) NOT NULL,
  `enquete_id` int(10) NOT NULL,
  `opcao_id` int(10) NOT NULL,
  `usuario_id` int(10) NOT NULL,
  `data_voto` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `enquetes`
--
ALTER TABLE `enquetes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `opcoes_enquete`
--
ALTER TABLE `opcoes_enquete`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `matricula_UNIQUE` (`matricula`);

--
-- Índices de tabela `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `enquetes`
--
ALTER TABLE `enquetes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `opcoes_enquete`
--
ALTER TABLE `opcoes_enquete`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `votos`
--
ALTER TABLE `votos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
