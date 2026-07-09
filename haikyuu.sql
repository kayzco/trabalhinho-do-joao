-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/07/2026 às 20:23
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `haikyuu`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcao`
--

CREATE TABLE `funcao` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcao`
--

INSERT INTO `funcao` (`id`, `nome`) VALUES
(1, 'Jogador'),
(2, 'Treinador'),
(3, 'Assistente'),
(4, 'Professor'),
(5, 'Família'),
(6, 'Outro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem`
--

CREATE TABLE `personagem` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `idade` int(11) DEFAULT NULL,
  `altura` int(11) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `id_time` int(11) DEFAULT NULL,
  `id_funcao` int(11) NOT NULL,
  `id_posicao` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem`
--

INSERT INTO `personagem` (`id`, `nome`, `idade`, `altura`, `numero`, `descricao`, `id_time`, `id_funcao`, `id_posicao`, `imagem`, `id_usuario`) VALUES
(1, 'Daichi Sawamura', NULL, NULL, NULL, NULL, 1, 1, 5, NULL, NULL),
(2, 'Koshi Sugawara', NULL, NULL, NULL, NULL, 1, 1, 3, NULL, NULL),
(3, 'Asahi Azumane', NULL, NULL, NULL, NULL, 1, 1, 2, NULL, NULL),
(4, 'Yu Nishinoya', NULL, NULL, NULL, NULL, 1, 1, 1, NULL, NULL),
(5, 'Ryunosuke Tanaka', NULL, NULL, NULL, NULL, 1, 1, 2, NULL, NULL),
(6, 'Chikara Ennoshita', NULL, NULL, NULL, NULL, 1, 1, 2, NULL, NULL),
(7, 'Kazuhito Narita', NULL, NULL, NULL, NULL, 1, 1, 4, NULL, NULL),
(8, 'Hisashi Kinoshita', NULL, NULL, NULL, NULL, 1, 1, 2, NULL, NULL),
(9, 'Tobio Kageyama', NULL, NULL, NULL, NULL, 1, 1, 3, NULL, NULL),
(10, 'Shoyo Hinata', NULL, NULL, NULL, NULL, 1, 1, 4, NULL, NULL),
(11, 'Kei Tsukishima', NULL, NULL, NULL, NULL, 1, 1, 4, NULL, NULL),
(12, 'Tadashi Yamaguchi', NULL, NULL, NULL, NULL, 1, 1, 4, NULL, NULL),
(13, 'Ittetsu Takeda', NULL, NULL, NULL, NULL, 1, 4, NULL, NULL, NULL),
(14, 'Kiyoko Shimizu', NULL, NULL, NULL, NULL, 1, 3, NULL, NULL, NULL),
(15, 'Hitoka Yachi', NULL, NULL, NULL, NULL, 1, 3, NULL, NULL, NULL),
(16, 'Keishin Ukai', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL),
(17, 'Ikkei Ukai', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL),
(21, 'seu ze', 17, 2, 11, 'seu ze', 22, 1, 3, NULL, NULL),
(22, 'kay', 17, 2, 9, 'eu sei escrever as vezes&#13;&#10;', 2, 1, 2, '373e6cd967f205fa9cd35ef6b2b48540.png', NULL),
(23, 'marie', 15, 2, 11, 'miau', 4, 1, 5, NULL, NULL),
(29, 'oij', 16, 2, 10, '', 2, 1, 1, NULL, NULL),
(31, 'seu ze', 13, 2, 22, 'sei q la', 3, 5, 4, '86943d336cab9c7df113137b80bf683e.png', 1),
(32, 'iup lero', 14, 2, 12, 'lero', 4, 1, 4, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem_tag`
--

CREATE TABLE `personagem_tag` (
  `id_personagem` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `posicao`
--

CREATE TABLE `posicao` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posicao`
--

INSERT INTO `posicao` (`id`, `nome`) VALUES
(1, 'Líbero'),
(2, 'Ponteiro'),
(3, 'Levantador'),
(4, 'Central'),
(5, 'Oposto');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tag`
--

INSERT INTO `tag` (`id`, `nome`) VALUES
(1, 'Capitão'),
(2, 'Levantador genial'),
(3, 'Bloqueador'),
(4, 'Baixinho caótico'),
(5, 'Saque poderoso'),
(6, 'Favorito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `time`
--

CREATE TABLE `time` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `lema` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `time`
--

INSERT INTO `time` (`id`, `nome`, `lema`) VALUES
(1, 'Karasuno', NULL),
(2, 'Nekoma', NULL),
(3, 'Aobajosai', NULL),
(4, 'Datekou', NULL),
(5, 'Fukurodani', NULL),
(6, 'Shiratorizawa', NULL),
(7, 'Johzenji', NULL),
(8, 'Tokonami', NULL),
(9, 'Wakutani Sul', NULL),
(10, 'Ougiminami', NULL),
(11, 'Kakugawa', NULL),
(12, 'Ubugawa', NULL),
(13, 'Shinzen', NULL),
(14, 'Nohebi', NULL),
(15, 'Itachiyama', NULL),
(16, 'Inarizaki', NULL),
(17, 'Tsubakihara', NULL),
(18, 'Niiyama', NULL),
(19, 'Hakusuikan', NULL),
(20, 'Yukigaoka', NULL),
(21, 'Fundamental', NULL),
(22, 'Outros', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `id_time` int(11) DEFAULT 1,
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `descricao`, `id_time`, `foto_perfil`) VALUES
(1, 'kay', 'kay@gmail.com', '123', 'lala', 1, NULL),
(2, 'jujubinha', 'jujuba@gmail.com', '123', 'futeboleira', 1, NULL),
(3, 'luluquinha', 'lulu@gmail.com', '123', 'pitaya', 2, NULL),
(4, 'aninha', 'tavares@gmail.com', '123', 'tarot', 3, NULL),
(13, 'Shoyo Hinata', 'hinata@gmail.com', '10', 'pequeno gigante', 1, NULL),
(14, 'kenma', 'kenma@gmail.com', '5', '', 2, NULL),
(15, 'seu ze', 'seuzezinho@gmail.com', '321', '', 6, NULL),
(16, 'aaa', 'aa@gmail.com', '12', '', 6, NULL),
(17, 'jujubinha', 'juj@gmail.com', '123', 'a menininha', 1, 'avatar_d2f5ef3136642b0899410121c4fc5e55.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `funcao`
--
ALTER TABLE `funcao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `personagem`
--
ALTER TABLE `personagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_time` (`id_time`),
  ADD KEY `id_funcao` (`id_funcao`),
  ADD KEY `fk_personagem_posicao` (`id_posicao`);

--
-- Índices de tabela `personagem_tag`
--
ALTER TABLE `personagem_tag`
  ADD PRIMARY KEY (`id_personagem`,`id_tag`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Índices de tabela `posicao`
--
ALTER TABLE `posicao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `funcao`
--
ALTER TABLE `funcao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `personagem`
--
ALTER TABLE `personagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `posicao`
--
ALTER TABLE `posicao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `time`
--
ALTER TABLE `time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `personagem`
--
ALTER TABLE `personagem`
  ADD CONSTRAINT `fk_personagem_posicao` FOREIGN KEY (`id_posicao`) REFERENCES `posicao` (`id`),
  ADD CONSTRAINT `personagem_ibfk_1` FOREIGN KEY (`id_time`) REFERENCES `time` (`id`),
  ADD CONSTRAINT `personagem_ibfk_2` FOREIGN KEY (`id_funcao`) REFERENCES `funcao` (`id`);

--
-- Restrições para tabelas `personagem_tag`
--
ALTER TABLE `personagem_tag`
  ADD CONSTRAINT `personagem_tag_ibfk_1` FOREIGN KEY (`id_personagem`) REFERENCES `personagem` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `personagem_tag_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
