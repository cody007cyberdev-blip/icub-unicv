-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24-Jun-2024 às 18:08
-- Versão do servidor: 8.3.0
-- versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_icub`
--
 Create Database db_icub;
 use db_icub;
-- --------------------------------------------------------

--
-- Estrutura da tabela `gestao_ativos`
--

DROP TABLE IF EXISTS `gestao_ativos`;
CREATE TABLE IF NOT EXISTS `gestao_ativos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text,
  `foto` varchar(255) DEFAULT NULL,
  `id_coordenador` int NOT NULL,
  `datahorario` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `link` varchar(255) DEFAULT NULL,
  `categoria` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_coordenador` (`id_coordenador`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `gestao_ativos`
--

INSERT INTO `gestao_ativos` (`id`, `titulo`, `descricao`, `foto`, `id_coordenador`, `datahorario`, `link`, `categoria`) VALUES
(3, 'dffff', 'fewewfwefwefwef', '1719052854U6676aa36b1eca_campus.jpg', 1, '2024-06-22 09:40:54', 'https://www.youtube.com/watch?v=PuwydNmz5QQ', 'Noticia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_avaliacao`
--

DROP TABLE IF EXISTS `tbl_avaliacao`;
CREATE TABLE IF NOT EXISTS `tbl_avaliacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ideia` int NOT NULL,
  `id_avaliador` int NOT NULL,
  `tipo_avaliador` enum('Coordenador','Supervisor') NOT NULL,
  `nota` int NOT NULL,
  `comentario` text,
  `status` varchar(255) DEFAULT NULL,
  `data_avaliacao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_ideia` (`id_ideia`),
  KEY `id_avaliador` (`id_avaliador`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_avaliacao`
--

INSERT INTO `tbl_avaliacao` (`id`, `id_ideia`, `id_avaliador`, `tipo_avaliador`, `nota`, `comentario`, `status`) VALUES
(1, 57, 1, 'Coordenador', 4, 'ok', 'Recusado'),
(2, 58, 1, 'Coordenador', 10, 'ok', 'Aprovado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_candidato`
--

DROP TABLE IF EXISTS `tbl_candidato`;
CREATE TABLE IF NOT EXISTS `tbl_candidato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` char(1) NOT NULL,
  `nacionalidade` varchar(255) NOT NULL,
  `contacto` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_entrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `id_equipa` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_candidato`
--

INSERT INTO `tbl_candidato` (`id`, `nome`, `data_nascimento`, `sexo`, `nacionalidade`, `contacto`, `endereco`, `email`, `foto`, `password`, `data_entrada`) VALUES
(147, 'Erica', '2024-06-10', 'M', 'Cabo-verdiana', '98827352', 'Lém-cachorro', 'Erica@student.unicv.edu.cv', '1718303951_campus.jpg', '$2y$10$Jtpz1DL55bdX2TLdekFXhOQ0njNRtU2/0CQQTQpmtT8WTZnfdWbLO', '0000-00-00 00:00:00'),
(152, 'Erica', '2024-06-10', 'M', 'Cabo-verdiana', '98827352', 'Lém-cachorro', 'erica@student.unicv.edu.cv', '1718303951_campus.jpg', '$2y$10$Jtpz1DL55bdX2TLdekFXhOQ0njNRtU2/0CQQTQpmtT8WTZnfdWbLO', '0000-00-00 00:00:00'),
(149, 'Hélio Cabral', '2002-06-12', 'M', 'Cabo Verde', '9834212', 'Plateau(Praia)', 'helio@student.unicv.edu.cv', '1718497954_helio cabral.jpeg', '$2y$10$M.jG8rm7gHqrPdjKoDDuke7nR4wezoSZLwcraTE53btrzxjedUHWi', '0000-00-00 00:00:00'),
(150, 'hoje teste', '2024-06-26', 'M', 'caboverdiano', '91919191', 'Praia', 'testehoje@gmail.com', '1718499500U666e38acb70cb', '$2y$10$je1nDphArnuC9Bp5gXppkOXr4OlSJWPFz6ueO5DcJh.vUbHPZTlv6', '0000-00-00 00:00:00'),
(156, 'elton', '2024-06-12', 'F', 'Cabo-verdiana', '963452', 'Plateau', 'elton@student.unicv.edu.cv', '1718043101_SSSBB.png', '$2y$10$5h8P7n8PD9y2KMOms5S5lel6bZf1PzW8fH0/a8kXj2EAg3uFfXg2K', '0000-00-00 00:00:00'),
(155, 'hoje teste', '2024-06-26', 'M', 'caboverdiano', '91919191', 'Praia', 'hoje.teste@gmail.com', '1718499500U666e38acb70cb', '$2y$10$je1nDphArnuC9Bp5gXppkOXr4OlSJWPFz6ueO5DcJh.vUbHPZTlv6', '0000-00-00 00:00:00'),
(154, 'Hélio Cabral', '2002-06-12', 'M', 'Cabo Verde', '9834212', 'Plateau(Praia)', 'helio@student.unicv.edu.cv', '1718497954_helio_cabral.jpeg', '$2y$10$M.jG8rm7gHqrPdjKoDDuke7nR4wezoSZLwcraTE53btrzxjedUHWi', '0000-00-00 00:00:00'),
(148, 'elton', '2024-06-12', 'F', 'Cabo-verdiana', '963452', 'Plateau', 'pedro.vaz@student.unicv.edu.cv', '1718043101_SSSBB.png', '$2y$10$5h8P7n8PD9y2KMOms5S5lel6bZf1PzW8fH0/a8kXj2EAg3uFfXg2K', '0000-00-00 00:00:00'),
(145, 'novocandidato', '2024-06-26', 'M', 'Cabo-verdiano', '99999', 'Palmarejo', 'novocand@student.unicv.edu.cv', '1717990330_unicv.png', '$2y$10$r6uBNG1ZqOCn5rjc3KPtuuQDyopdowFTNpk94XsW3PwHN9oZzqxUq', '0000-00-00 00:00:00'),
(153, 'Aristides', '2024-06-05', 'M', 'Cabo-verdiana', '963452', 'Palmarejo', 'aristides@student.unicv.edu.cv', '', '$2y$10$O2FkQ23a1/jD4f2ZtQ6AaOgnU2Xk/O4Zz/1tFdQiyCewDOPVmV7Zu', '0000-00-00 00:00:00'),
(151, 'teste2', '2024-06-22', 'M', 'caboverdiano', '91919191', 'Safende', 'teste2@gmail.com', '1718499613U666e391daa497', '$2y$10$xu1p69Zgv9oDWn/4BOqi2uUvwSWqtsOmMqdoHl4KO8kl/mpxwyhQe', '0000-00-00 00:00:00'),
(142, 'Teste 1', '2024-06-18', 'M', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'soucandidato@student.unicv.edu.cv', '1717990086_SSSBB.png', '$2y$10$1DVvw7tPTq0wdxO7E1x70eNRAq.3Hn72W4J4iWqVdFeVOZlW/znJq', '0000-00-00 00:00:00'),
(141, 'Teste Candidato', '2024-06-13', 'F', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'tes@student.unicv.edu.cv', '1717989551_1716736612_20230213_191021.jpg', '$2y$10$Sbz.QA8skpay.0uSug07z.3rYFnsUUFaDkjWB4w8Hg6bT6wXOTZmO', '0000-00-00 00:00:00'),
(138, 'Pedro', '2001-05-15', 'M', 'Cabo-verdiano', '9765432', 'Lém-cachorro', 'pedro.vaz@student.unicv.edu.cv', '1717986641_desenho.jpeg', '$2y$10$yRNYUX9gRIbZb0epl3i5veI4Q4SlGffedu2tN/87QG83xYwIyjSQy', '0000-00-00 00:00:00'),
(1, 'Pedro', '2024-06-02', 'M', 'Cabo-verdiano', '9855393', 'Praia', 'pedro.vaz@student.unicv.edu.cv', 'desenho.jpeg', '10pedro', '0000-00-00 00:00:00'),
(79, 'elton', '2024-06-04', 'M', 'Cabo-verdiana', '963452', 'Plateau', 'alvarooo@student.unicv.edu.cv', '1717460945_logoicub.png', '12', '0000-00-00 00:00:00'),
(140, 'Teste Candidato', '2024-06-19', 'M', 'Cabo-verdiana', '9876543', 'Praia', 'testecandidato@student.unicv.edu.cv', '1717987944_unicv.png', '$2y$10$BxcDhnP4VO.oDm2KMm4/uOC0aoPM0ReVRan4ywmWRIvSWIerBfdIm', '0000-00-00 00:00:00'),
(157, 'novocandidato', '2024-06-26', 'M', 'Cabo-verdiano', '99999', 'Palmarejo', 'novocand@student.unicv.edu.cv', '1717990330_unicv.png', '$2y$10$r6uBNG1ZqOCn5rjc3KPtuuQDyopdowFTNpk94XsW3PwHN9oZzqxUq', '0000-00-00 00:00:00'),
(158, 'teste2', '2024-06-22', 'M', 'caboverdiano', '91919191', 'Safende', 'teste2@gmail.com', '1718499613U666e391daa497', '$2y$10$xu1p69Zgv9oDWn/4BOqi2uUvwSWqtsOmMqdoHl4KO8kl/mpxwyhQe', '0000-00-00 00:00:00'),
(159, 'Teste 1', '2024-06-18', 'M', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'soucandidato@student.unicv.edu.cv', '1717990086_SSSBB.png', '$2y$10$1DVvw7tPTq0wdxO7E1x70eNRAq.3Hn72W4J4iWqVdFeVOZlW/znJq', '0000-00-00 00:00:00'),
(160, 'Teste Candidato', '2024-06-13', 'F', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'teste.candidato@student.unicv.edu.cv', '1717989551_1716736612_20230213_191021.jpg', '$2y$10$Sbz.QA8skpay.0uSug07z.3rYFnsUUFaDkjWB4w8Hg6bT6wXOTZmO', '0000-00-00 00:00:00'),
(161, 'Pedro', '2001-05-15', 'M', 'Cabo-verdiano', '9765432', 'Lém-cachorro', 'pedro.vaz2@student.unicv.edu.cv', '1717986641_desenho.jpeg', '$2y$10$yRNYUX9gRIbZb0epl3i5veI4Q4SlGffedu2tN/87QG83xYwIyjSQy', '0000-00-00 00:00:00'),
(162, 'Erica', '2024-06-10', 'M', 'Cabo-verdiana', '98827352', 'Lém-cachorro', 'erica@student.unicv.edu.cv', '1718303951_campus.jpg', '$2y$10$Jtpz1DL55bdX2TLdekFXhOQ0njNRtU2/0CQQTQpmtT8WTZnfdWbLO', '0000-00-00 00:00:00'),
(163, 'Aristides', '2024-06-05', 'M', 'Cabo-verdiana', '963452', 'Palmarejo', 'aristides@student.unicv.edu.cv', '', '$2y$10$O2FkQ23a1/jD4f2ZtQ6AaOgnU2Xk/O4Zz/1tFdQiyCewDOPVmV7Zu', '0000-00-00 00:00:00'),
(164, 'Hélio Cabral', '2002-06-12', 'M', 'Cabo Verde', '9834212', 'Plateau(Praia)', 'helio@student.unicv.edu.cv', '1718497954_helio_cabral.jpeg', '$2y$10$M.jG8rm7gHqrPdjKoDDuke7nR4wezoSZLwcraTE53btrzxjedUHWi', '0000-00-00 00:00:00'),
(165, 'hoje teste', '2024-06-26', 'M', 'caboverdiano', '91919191', 'Praia', 'hoje.teste@gmail.com', '1718499500U666e38acb70cb', '$2y$10$je1nDphArnuC9Bp5gXppkOXr4OlSJWPFz6ueO5DcJh.vUbHPZTlv6', '0000-00-00 00:00:00'),
(166, 'elton', '2024-06-12', 'F', 'Cabo-verdiana', '963452', 'Plateau', 'elton@student.unicv.edu.cv', '1718043101_SSSBB.png', '$2y$10$5h8P7n8PD9y2KMOms5S5lel6bZf1PzW8fH0/a8kXj2EAg3uFfXg2K', '0000-00-00 00:00:00'),
(167, 'novocandidato', '2024-06-26', 'M', 'Cabo-verdiano', '99999', 'Palmarejo', 'novocand@student.unicv.edu.cv', '1717990330_unicv.png', '$2y$10$r6uBNG1ZqOCn5rjc3KPtuuQDyopdowFTNpk94XsW3PwHN9oZzqxUq', '0000-00-00 00:00:00'),
(168, 'teste2', '2024-06-22', 'M', 'caboverdiano', '91919191', 'Safende', 'teste2@gmail.com', '1718499613U666e391daa497', '$2y$10$xu1p69Zgv9oDWn/4BOqi2uUvwSWqtsOmMqdoHl4KO8kl/mpxwyhQe', '0000-00-00 00:00:00'),
(170, 'Teste Candidato', '2024-06-13', 'F', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'teste.candidato@student.unicv.edu.cv', '1717989551_1716736612_20230213_191021.jpg', '$2y$10$Sbz.QA8skpay.0uSug07z.3rYFnsUUFaDkjWB4w8Hg6bT6wXOTZmO', '0000-00-00 00:00:00'),
(171, 'Pedro', '2001-05-15', 'M', 'Cabo-verdiano', '9765432', 'Lém-cachorro', 'pedro.vaz2@student.unicv.edu.cv', '1717986641_desenho.jpeg', '$2y$10$yRNYUX9gRIbZb0epl3i5veI4Q4SlGffedu2tN/87QG83xYwIyjSQy', '0000-00-00 00:00:00'),
(172, 'Erica', '2024-06-10', 'M', 'Cabo-verdiana', '98827352', 'Lém-cachorro', 'erica@student.unicv.edu.cv', '1718303951_campus.jpg', '$2y$10$Jtpz1DL55bdX2TLdekFXhOQ0njNRtU2/0CQQTQpmtT8WTZnfdWbLO', '0000-00-00 00:00:00'),
(173, 'Aristides', '2024-06-05', 'M', 'Cabo-verdiana', '963452', 'Palmarejo', 'aristides@student.unicv.edu.cv', '', '$2y$10$O2FkQ23a1/jD4f2ZtQ6AaOgnU2Xk/O4Zz/1tFdQiyCewDOPVmV7Zu', '0000-00-00 00:00:00'),
(174, 'Hélio Cabral', '2002-06-12', 'M', 'Cabo Verde', '9834212', 'Plateau(Praia)', 'helio@student.unicv.edu.cv', '1718497954_helio_cabral.jpeg', '$2y$10$M.jG8rm7gHqrPdjKoDDuke7nR4wezoSZLwcraTE53btrzxjedUHWi', '0000-00-00 00:00:00'),
(175, 'hoje teste', '2024-06-26', 'M', 'caboverdiano', '91919191', 'Praia', 'hoje.teste@gmail.com', '1718499500U666e38acb70cb', '$2y$10$je1nDphArnuC9Bp5gXppkOXr4OlSJWPFz6ueO5DcJh.vUbHPZTlv6', '0000-00-00 00:00:00'),
(176, 'elton', '2024-06-12', 'F', 'Cabo-verdiana', '963452', 'Plateau', 'elton@student.unicv.edu.cv', '1718043101_SSSBB.png', '$2y$10$5h8P7n8PD9y2KMOms5S5lel6bZf1PzW8fH0/a8kXj2EAg3uFfXg2K', '0000-00-00 00:00:00'),
(177, 'novocandidato', '2024-06-26', 'M', 'Cabo-verdiano', '99999', 'Palmarejo', 'novocand@student.unicv.edu.cv', '1717990330_unicv.png', '$2y$10$r6uBNG1ZqOCn5rjc3KPtuuQDyopdowFTNpk94XsW3PwHN9oZzqxUq', '0000-00-00 00:00:00'),
(178, 'teste2', '2024-06-22', 'M', 'caboverdiano', '91919191', 'Safende', 'teste2@gmail.com', '1718499613U666e391daa497', '$2y$10$xu1p69Zgv9oDWn/4BOqi2uUvwSWqtsOmMqdoHl4KO8kl/mpxwyhQe', '0000-00-00 00:00:00'),
(179, 'Teste 1', '2024-06-18', 'M', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'soucandidato@student.unicv.edu.cv', '1717990086_SSSBB.png', '$2y$10$1DVvw7tPTq0wdxO7E1x70eNRAq.3Hn72W4J4iWqVdFeVOZlW/znJq', '0000-00-00 00:00:00'),
(180, 'Teste Candidato', '2024-06-13', 'F', 'Cabo-verdiana', '9876543', 'Lém-cachorro', 'teste.candidato@student.unicv.edu.cv', '1717989551_1716736612_20230213_191021.jpg', '$2y$10$Sbz.QA8skpay.0uSug07z.3rYFnsUUFaDkjWB4w8Hg6bT6wXOTZmO', '0000-00-00 00:00:00'),
(181, 'Pedro', '2001-05-15', 'M', 'Cabo-verdiano', '9765432', 'Lém-cachorro', 'pedro.vaz2@student.unicv.edu.cv', '1717986641_desenho.jpeg', '$2y$10$yRNYUX9gRIbZb0epl3i5veI4Q4SlGffedu2tN/87QG83xYwIyjSQy', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_candidaturas`
--

DROP TABLE IF EXISTS `tbl_candidaturas`;
CREATE TABLE IF NOT EXISTS `tbl_candidaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `candidato_principal` varchar(10) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `data_nasc` date NOT NULL,
  `genero` varchar(50) NOT NULL,
  `doc_identifi` varchar(100) NOT NULL,
  `area_formacao` varchar(255) DEFAULT NULL,
  `ano_curso` varchar(255) DEFAULT NULL,
  `num_estudante` varchar(100) DEFAULT NULL,
  `doc_comprovativo` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nif` varchar(50) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `telemovel` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_candidato` int NOT NULL,
  `avaliacao` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_submissao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_candidaturas`
--

INSERT INTO `tbl_candidaturas` (`id`, `candidato_principal`, `nome`, `data_nasc`, `genero`, `doc_identifi`, `area_formacao`, `ano_curso`, `num_estudante`, `doc_comprovativo`, `endereco`, `nif`, `telefone`, `telemovel`, `email`, `id_candidato`, `avaliacao`) VALUES
(57, 'Sim', 'Hélio Cabral', '2002-06-12', 'Masculino', '1719032783_cni 34.pdf', 'Engenharia Informática e de Computadores', '3º Ano', '131213', '1719032783_declaraçao de Frequência UNICV.pdf', 'Plateau (Praia)', '123456789', '2123456', '9123456', 'helio@student.unicv.edu.cv', 149, 'Recusado'),
(58, 'Sim', 'Erica', '2024-06-10', 'Masculino', '1719033030_carta (3).pdf', 'Medicina', '3º Ano', '134253', '1719033030_declaraçao de Frequência UNICV.pdf', 'Lém-cachorro', '987654321', '', '5232423', 'Erica@student.unicv.edu.cv', 147, 'Aprovado'),
(59, 'Sim', 'Erica', '2024-06-10', 'Masculino', '1719167221_TESTE-1-ASA2021.pdf', 'Medicina', '2º Ano', '134253', '1719167221_TESTE-1-ASA2021.pdf', 'Lém-cachorro', '65432', '543232', '4532', 'Erica@student.unicv.edu.cv', 147, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_coordenador`
--

DROP TABLE IF EXISTS `tbl_coordenador`;
CREATE TABLE IF NOT EXISTS `tbl_coordenador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `sexo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contacto` varchar(50) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_entrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_coordenador`
--

INSERT INTO `tbl_coordenador` (`id`, `nome`, `sexo`, `contacto`, `endereco`, `email`, `password`, `foto`, `data_entrada`) VALUES
(1, 'Edson Vaz', 'M', '9855393', 'Lém-cachorro', 'edsonvazeic@gmail.com', '$2y$10$U85S3Dbr5b/qcd9o577n1.6ujUKOdcKL9/Qv7G06q5ZvF75EgS976', '1718905365_evaz.jpg', '0000-00-00 00:00:00'),
(2, 'Elvis Vaz', 'M', '98827352', 'Achada Trás', 'elvisvazeic3@gmail.com', '2003', '1716869848_elvis.jpg', '0000-00-00 00:00:00'),
(3, 'Leonardo Fonseca', 'M', '9564353', 'Achada Trás', 'leonardofonseca@gmail.com', '$2y$10$e18aic2VvQQbB6QsEQVy2u.zSkVhqj6L5iiWoDAjMHGyTBZlqblCS', '1717462201_crown.jpeg', '0000-00-00 00:00:00'),
(11, 'Novo Coordenadora', 'Feminino', '9343536', 'Palmarejo', 'novocoordenadora@gmail.com', '$2y$10$VByi7447ihUrPCT8F9pUuOS9dw7jfn37qfGAxi/cZUTdvUVavH4xC', '1719156643_imagec.jpg', '2024-06-23 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_identificacao_ideia`
--

DROP TABLE IF EXISTS `tbl_identificacao_ideia`;
CREATE TABLE IF NOT EXISTS `tbl_identificacao_ideia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_candidatura` int NOT NULL,
  `titulo_ideia` varchar(255) NOT NULL,
  `sector` varchar(255) NOT NULL,
  `descri_conceito` text,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `info_complementar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `doc_apresent` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `data_submissao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_candidato` (`id_candidatura`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_identificacao_ideia`
--

INSERT INTO `tbl_identificacao_ideia` (`id`, `id_candidatura`, `titulo_ideia`, `sector`, `descri_conceito`, `estado`, `info_complementar`, `doc_apresent`, `video`, `data_submissao`) VALUES
(57, 57, 'Sistema de Gestão de Inventário com IA (InvenTrack)', '1.Tecnologia da Informação (TI)', 'InvenTrack é um sistema inovador de gestão de inventário que utiliza inteligência artificial para otimizar o controle e a rastreabilidade dos produtos. O objetivo principal é reduzir perdas, melhorar a precisão do inventário e fornecer análises preditivas para auxiliar na tomada de decisão.', 'Fase de Desenvolvimento', 'Atualmente, o projeto InvenTrack está na fase de desenvolvimento. Estamos finalizando a implementação dos algoritmos de IA e realizando testes internos para garantir a eficácia do sistema. Planejamos iniciar um programa piloto com um parceiro de negócios nos próximos três meses.\r\n', '1719032783_Logica e tela para terminar no projeto.docx', '1719032783_O que é Tecnologia da Informação - TI_ Aprenda TI em 2 Minutos.mp4', '2024-06-22 00:00:00'),
(58, 58, 'Desenvolvimento de Dispositivo Portátil para Monitoramento Cardíaco (CardioMonitor)', '3.Saúde', 'O CardioMonitor é um dispositivo portátil e não invasivo destinado ao monitoramento contínuo de sinais vitais cardíacos, como frequência cardíaca, arritmias e pressão arterial. O objetivo é proporcionar aos pacientes e profissionais de saúde uma ferramenta acessível e eficaz para o gerenciamento preventivo de condições cardíacas.\r\n', 'Prototipagem', 'Atualmente, estamos na fase de prototipagem, onde estamos refinando os designs técnicos e realizando testes preliminares de funcionalidade. Nossa equipe de desenvolvimento está focada na otimização da precisão dos dados coletados e na garantia da facilidade de uso do dispositivo por parte dos usuários finais.\r\n\r\n', '1719033030_recibo.pdf', '1719033030_istockphoto-1492965725-640_adpp_is.mp4', '2024-06-22 00:00:00'),
(59, 59, 'Desenvolvimento de Dispositivo Portátil para Monitoramento Cardíaco (CardioMonitor)', '3.Saúde', '990876ytrew', 'Prototipagem', 'o98iuyhrgfed', '1719167221_Logica e tela para terminar no projeto.docx', '1719167221_', '2024-06-23 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_projeto`
--

DROP TABLE IF EXISTS `tbl_projeto`;
CREATE TABLE IF NOT EXISTS `tbl_projeto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_projeto` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `data_inicio` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `data_fim` date NOT NULL,
  `id_supervisor` int NOT NULL,
  `id_ideia` int NOT NULL,
  `status_projeto` enum('Em andamento','Concluído','Cancelado') NOT NULL DEFAULT 'Em andamento',
  PRIMARY KEY (`id`),
  KEY `id_supervisor` (`id_supervisor`),
  KEY `id_ideia` (`id_ideia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_supervisor`
--

DROP TABLE IF EXISTS `tbl_supervisor`;
CREATE TABLE IF NOT EXISTS `tbl_supervisor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `sexo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contacto` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `nacionalidade` varchar(255) NOT NULL,
  `area_atuacao` varchar(255) NOT NULL,
  `experiencia_profissional` mediumtext NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `data_entrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_supervisor`
--

INSERT INTO `tbl_supervisor` (`id`, `nome`, `sexo`, `contacto`, `endereco`, `email`, `data_nascimento`, `nacionalidade`, `area_atuacao`, `experiencia_profissional`, `foto`, `password`, `data_entrada`) VALUES
(1, 'Julio Barros', 'Masculino', '9066345', 'Achada Trás', 'juliobarros@gmail.com', '1999-05-15', 'Cabo-verdiano', 'Professor informático', 'mestrado e doutorado em engenharia e informática de computadores', '1717178605_diamante.png', 'supervisor20', '0000-00-00 00:00:00'),
(5, 'evaz', 'Masculino', '9678933', 'Lém-cachorro', 'edsonvazeic@gmail.com', '2024-05-31', 'Cabo-verdiana', 'programador', 'Informatica', '1717174699_arroz.jpg', '123456', '0000-00-00 00:00:00');
COMMIT;


CREATE TABLE IF NOT EXISTS tbl_equipa (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_projetos` INT NOT NULL,
  `nomeequipa` VARCHAR(255) NOT NULL,
  `dataentrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_equipa`),
  FOREIGN KEY (`id_projetos`) REFERENCES tbl_projeto(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;