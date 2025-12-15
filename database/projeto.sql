-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/12/2025 às 15:58
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

--
-- Despejando dados para a tabela `enquetes`
--

INSERT INTO `enquetes` (`id`, `pergunta`, `ativa`, `data_criacao`, `destaque`) VALUES
(1, 'Cardápio de sexta-feira - Qual sua preferência?', 1, '2025-11-07 19:19:22', 1),
(2, 'Qual tema preferem para a Feira de Ciências?', 1, '2025-11-07 19:22:56', 0),
(3, 'Qual atividade extracurricular você gostaria que a escola oferecesse?', 1, '2025-11-07 19:24:44', 0);

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
(12, 3, 'Aulas de Robótica');

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

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `matricula`, `data_nascimento`, `endereco`, `nivel_acesso`) VALUES
(1, 'Joana da Silva', 'joanasilva@escola.com', 'joana123', '2048569', '2012-08-15', 'Rua da Alegria, 245', 'aluno'),
(2, 'Gabriel Ferreira', 'gabrielferreira@escola.com', 'gabriel123', '2059214', '2015-05-15', 'Rua do Girassol, 796', 'aluno'),
(3, 'João Gomes Lima', 'joaoglima@escola.com', 'joaoglima123', '2087456', '2012-04-20', 'Rua da Aurora, 325', 'aluno'),
(7, 'Administrador', 'admin@escola.com', 'admin123', '0000000', '1992-06-03', 'Setor Administrativo', 'admin'),
(10, 'Alicia Carvalho', 'aliciacar@escola.com', 'alicia123', '2034891', '2014-08-04', 'Rua da Saudade, 986', 'aluno'),
(11, 'Rogerio Arcanjo', 'rogerioa@escola.com', 'rogerio123', '2099836', '2013-07-28', 'Rua do Céu, 776', 'aluno'),
(12, 'Rosana Figueira', 'rosanafig@escola.com', 'rosana123', '2087653', '2017-11-10', 'Rua do Sol, 584', 'aluno'),
(13, 'Aline Barros', 'alineb@escola.com', 'aline123', '2098271', '2014-07-12', 'Rua das Flores, 123', 'aluno'),
(14, 'Carlos Pereira', 'carlosp@escola.com', 'carlos123', '2086671', '2017-01-05', 'Rua das Ameixas, 429', 'aluno'),
(15, 'Diana Mota', 'dianamota@escola.com', 'diana123', '2087465', '2016-07-14', 'Rua do Vale, 987', 'aluno'),
(16, 'Luzia Santos', 'luziasantos@escola.com', 'luzia123', '2019348', '2015-05-19', 'Rua do Vale, 987', 'aluno'),
(17, 'Jhon Cordeiro', 'jhonc@escola.com', 'jhon123', '20394738', '2016-06-10', 'Rua da Valentina, 387', 'aluno');

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
-- Despejando dados para a tabela `votos`
--

INSERT INTO `votos` (`id`, `enquete_id`, `opcao_id`, `usuario_id`, `data_voto`) VALUES
(1, 1, 2, 1, '2025-11-07 20:23:32'),
(2, 1, 1, 3, '2025-11-07 20:29:03'),
(3, 1, 2, 10, '2025-11-07 20:31:53'),
(4, 1, 2, 2, '2025-11-07 20:35:15'),
(5, 2, 8, 2, '2025-11-07 21:04:06'),
(6, 3, 11, 2, '2025-11-07 21:04:10'),
(7, 2, 5, 1, '2025-11-09 01:41:01'),
(8, 3, 10, 1, '2025-11-09 01:41:08'),
(9, 1, 3, 11, '2025-11-09 02:20:48'),
(10, 2, 8, 11, '2025-11-09 02:21:03'),
(11, 3, 12, 11, '2025-11-09 02:21:14'),
(12, 1, 1, 12, '2025-11-10 22:41:45'),
(13, 2, 5, 12, '2025-11-10 22:41:51'),
(14, 3, 11, 12, '2025-11-10 22:41:55'),
(15, 1, 3, 13, '2025-11-14 00:01:07'),
(16, 2, 7, 13, '2025-11-14 00:01:17'),
(17, 3, 9, 13, '2025-11-14 00:01:25'),
(18, 1, 2, 14, '2025-11-16 18:09:13'),
(19, 2, 6, 14, '2025-11-16 18:09:25'),
(20, 3, 12, 14, '2025-11-16 18:09:35'),
(21, 1, 4, 15, '2025-11-18 18:46:31'),
(22, 2, 7, 15, '2025-11-18 18:46:40'),
(23, 3, 11, 15, '2025-11-18 18:46:46'),
(24, 1, 2, 16, '2025-11-24 22:09:35'),
(25, 2, 5, 16, '2025-11-24 22:09:55'),
(26, 3, 12, 16, '2025-11-24 22:10:14'),
(27, 1, 1, 17, '2025-11-27 22:31:58'),
(28, 2, 5, 17, '2025-11-27 22:32:13'),
(29, 3, 11, 17, '2025-11-27 22:32:21');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `enquetes`
--
ALTER TABLE `enquetes`
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `opcoes_enquete`
--
ALTER TABLE `opcoes_enquete`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `votos`
--
ALTER TABLE `votos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
