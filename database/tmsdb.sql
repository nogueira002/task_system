-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21-Fev-2024 às 23:29
-- Versão do servidor: 8.0.31
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tmsdb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `project_list`
--

DROP TABLE IF EXISTS `project_list`;
CREATE TABLE IF NOT EXISTS `project_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `manager_id` int NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `documents` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `project_list`
--

INSERT INTO `project_list` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`, `documents`) VALUES
(12, 'Plataforma de Troca de Tarefas Locais', '												&lt;font color=&quot;#000000&quot; face=&quot;S&ouml;hne, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif, Helvetica Neue, Arial, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji&quot;&gt;&lt;span style=&quot;white-space-collapse: preserve;&quot;&gt;Criar uma plataforma online onde os membros da comunidade possam oferecer e solicitar servi&ccedil;os locais uns aos outros em troca de pontos. Os usu&aacute;rios podem listar tarefas que precisam ser feitas, como jardinagem, conserto de m&oacute;veis, aulas particulares, etc., e tamb&eacute;m oferecer suas pr&oacute;prias habilidades para ajudar os outros. Cada vez que um usu&aacute;rio completa uma tarefa para outra pessoa, ele recebe pontos que podem ser usados para solicitar ajuda de outras pessoas na plataforma.&lt;/span&gt;&lt;/font&gt;&lt;br&gt;										', 0, '2024-02-19', '2024-03-12', 5, '3,7,4,2,8', '2024-02-12 18:10:49', NULL),
(15, 'Site SCI', '', 0, '1212-12-12', '1213-12-12', 5, '7', '2024-02-16 15:17:16', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'SISTask', 'info@sistrask.comm', '+351919343352', 'Porto, Portugal', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `task_list`
--

DROP TABLE IF EXISTS `task_list`;
CREATE TABLE IF NOT EXISTS `task_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `task` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completion_status` int DEFAULT '0',
  `document_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `task_list`
--

INSERT INTO `task_list` (`id`, `project_id`, `employee_id`, `task`, `description`, `status`, `date_created`, `completion_status`, `document_path`) VALUES
(19, 12, 3, 'Desenvolvimento de Protótipos', '								&lt;span style=&quot;font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;Crie prot&oacute;tipos de um produto ou servi&ccedil;o para testar sua viabilidade e funcionalidade.&lt;/font&gt;&lt;/span&gt;													', 3, '2024-02-12 18:11:48', 0, NULL),
(20, 12, 4, 'Análise de Dados', '								&lt;span style=&quot;font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;font color=&quot;#000000&quot; style=&quot;&quot;&gt;Colete e analise dados relevantes para extrair insights &uacute;teis e informar decis&otilde;es estrat&eacute;gicas.&lt;/font&gt;&lt;/span&gt;													', 3, '2024-02-12 18:14:40', 0, NULL),
(21, 12, 3, 'Desenvolvimento de Software', '				&lt;span style=&quot;font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;Projete e desenvolva aplicativos, programas ou sistemas de software para resolver problemas espec&iacute;ficos ou atender &agrave;s necessidades do seu p&uacute;blico-alvo.&lt;/font&gt;&lt;/span&gt;										', 3, '2024-02-12 18:15:09', 0, NULL),
(22, 12, 3, 'Gestão de Finanças', '				&lt;span style=&quot;font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;Monitore e gerencie as finan&ccedil;as do projeto, incluindo or&ccedil;amento, previs&atilde;o de receitas e despesas, e relat&oacute;rios financeiros.cccc&lt;/font&gt;&lt;/span&gt;', 1, '2024-02-12 18:16:10', 0, NULL),
(23, 12, 3, 'Treinamento e Desenvolvimento', '&lt;span style=&quot;font-family: S&ouml;hne, ui-sans-serif, system-ui, -apple-system, &amp;quot;Segoe UI&amp;quot;, Roboto, Ubuntu, Cantarell, &amp;quot;Noto Sans&amp;quot;, sans-serif, &amp;quot;Helvetica Neue&amp;quot;, Arial, &amp;quot;Apple Color Emoji&amp;quot;, &amp;quot;Segoe UI Emoji&amp;quot;, &amp;quot;Segoe UI Symbol&amp;quot;, &amp;quot;Noto Color Emoji&amp;quot;; white-space-collapse: preserve;&quot;&gt;&lt;font color=&quot;#000000&quot;&gt;Crie programas de treinamento para melhorar as habilidades e compet&ecirc;ncias dos funcion&aacute;rios ou membros da equipe.&lt;/font&gt;&lt;/span&gt;							', 1, '2024-02-12 18:16:42', 0, NULL),
(24, 15, 7, 'Criaçao do Logo', '														', 3, '2024-02-16 15:18:09', 0, NULL),
(25, 12, 2, 'teste', 'ola teste email', 1, '2024-02-21 21:33:32', 0, NULL),
(26, 12, 2, 'teste', 'ola teste email 2', 1, '2024-02-21 21:34:58', 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1 = admin, 2 = staff',
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@sis.pt', '0192023a7bbd73250516f069df18b500', 1, 'no-image-available.png', '2020-11-26 10:57:04'),
(2, 'Ricardo', 'Nogueira', 'ricardo@sis.com', 'f2a46c2e2f0b81c20b9ed0a7643d179f', 2, '1606978560_avatar.jpg', '2020-12-03 09:26:03'),
(3, 'Clara', 'Blake', 'clara@sis.com', 'aabc8a500e43c8cd96774aa15f17ca4d', 3, '1606958760_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 09:26:42'),
(4, 'Jorge', 'Wilson', 'jorge@sis.com', '5a0f035db329cea241ae3509ad2b824f', 3, '1606963560_avatar.jpg', '2020-12-03 10:46:41'),
(5, 'Samuel ', 'ribeiro', 'samuel@sis.com', 'e6fb448feb2fa877aab63b3713027775', 2, '1606963620_47446233-clean-noir-et-gradient-sombre-image-de-fond-abstrait-.jpg', '2020-12-03 10:47:06'),
(6, 'Ruben', 'Cunha', 'ruben@sis.com', '54e1ff846a06bb0fb04f3f7eec237c0e', 3, '', '2024-01-31 11:20:58'),
(7, 'Gonçalo ', 'Crespo', 'crespo@sis.com', '5a24aa6743e867684085bec605204d86', 3, '1707167760_9131529.png', '2024-01-31 11:21:33'),
(8, 'Rodrigo', 'Ribeiro', 'rodrigo@sis.com', 'bd3711d0dd00de22e9d2fb6c1bdd85d6', 3, '1707301140_9131529.png', '2024-01-31 11:22:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_productivity`
--

DROP TABLE IF EXISTS `user_productivity`;
CREATE TABLE IF NOT EXISTS `user_productivity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `comment` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int NOT NULL,
  `time_rendered` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `documents` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `user_productivity`
--

INSERT INTO `user_productivity` (`id`, `project_id`, `comment`, `subject`, `date`, `start_time`, `end_time`, `user_id`, `time_rendered`, `date_created`, `documents`) VALUES
(20, 15, 'ygtvytvy', '<fzsfzfzwfz', '2024-02-16', '15:19:00', '00:00:00', 5, -15.3167, '2024-02-16 15:19:59', NULL),
(21, 12, '&lt;p&gt;kakakaka&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;', 'Marketing Digital', '2024-02-18', '01:02:00', '00:00:00', 1, -1.03333, '2024-02-18 01:01:08', NULL),
(22, 12, 'adwdawdawdawdawdawd', 'Bug na basse de dados', '2024-02-18', '01:03:00', '00:00:00', 3, -1.05, '2024-02-18 01:03:19', NULL),
(23, 12, 'awdawdawda', 'Bug', '2024-02-21', '23:41:00', '00:00:00', 5, -23.6833, '2024-02-21 22:39:37', NULL),
(24, 12, '&lt;p&gt;teste&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;', 'Bug', '2024-02-21', '22:45:00', '00:00:00', 5, -22.75, '2024-02-21 22:45:41', NULL),
(27, 12, 'awdawdawdawda', 'Bug', '2024-02-21', '23:07:00', '00:00:00', 5, -23.1167, '2024-02-21 23:07:39', 'doc/41pCKJ+g11L._AC_SX522_.jpg');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `task_list`
--
ALTER TABLE `task_list`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
