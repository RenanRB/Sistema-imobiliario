-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2016 at 12:36 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_imobiliario`
--

-- --------------------------------------------------------

--
-- Table structure for table `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `cidade` varchar(256) NOT NULL,
  `bairro` varchar(256) NOT NULL,
  `rua` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enderecos`
--

INSERT INTO `enderecos` (`id`, `cep`, `estado`, `cidade`, `bairro`, `rua`) VALUES
(1, '89253-600', 'SC', 'Jaraguá do Sul', 'Tifa Martins', 'Francisco Hrushka'),
(2, '89035-300', 'SC', 'Blumenau', 'Vila Nova', 'Theodoro Holtrup');

-- --------------------------------------------------------

--
-- Table structure for table `imoveis`
--

CREATE TABLE `imoveis` (
  `id` int(11) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `suites` int(2) NOT NULL,
  `quartos` int(2) NOT NULL,
  `area_privativa` int(11) NOT NULL,
  `area_total` int(11) NOT NULL,
  `vaga_garagem` int(2) NOT NULL,
  `numero` int(11) NOT NULL,
  `caracteristicas` text NOT NULL,
  `informacoes_adicionais` text NOT NULL,
  `id_endereco` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `imoveis`
--

INSERT INTO `imoveis` (`id`, `nome`, `suites`, `quartos`, `area_privativa`, `area_total`, `vaga_garagem`, `numero`, `caracteristicas`, `informacoes_adicionais`, `id_endereco`, `id_usuario`) VALUES
(6, 'predio12', 1, 2, 3, 4, 5, 6, 'teste', 'testes', 1, 2),
(7, 'predio 2', 2, 3, 4, 5, 6, 7, 'teste45s4', 'fdsafds', 1, 2),
(8, 'predio 3', 5, 6, 7, 8, 9, 0, 'fsdfsdf', 'gfdsgfsdg', 2, 2),
(9, 'predio 5', 5, 3, 1, 5, 3, 54, '5', '2fsdf', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `dt_nascimento` date NOT NULL,
  `senha` varchar(32) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `nivel` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `dt_nascimento`, `senha`, `ativo`, `nivel`) VALUES
(2, 'Renan1', 'renan10r_@hotmail.com', '1993-12-10', '6116afedcb0bc31083935c1c262ff4c9', 1, 0),
(3, 'Usuario 2', 'teste@teste.com', '1995-12-10', '6116afedcb0bc31083935c1c262ff4c9', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imoveis`
--
ALTER TABLE `imoveis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_enderecos_imoveis` (`id_endereco`),
  ADD KEY `id_imoveis_usuarios` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `imoveis`
--
ALTER TABLE `imoveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `imoveis`
--
ALTER TABLE `imoveis`
  ADD CONSTRAINT `id_enderecos_imoveis` FOREIGN KEY (`id_endereco`) REFERENCES `enderecos` (`id`),
  ADD CONSTRAINT `id_imoveis_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
