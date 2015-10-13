-- phpMyAdmin SQL Dump
-- version 3.3.10.5
-- http://www.phpmyadmin.net
--
-- ホスト: mysql484.db.sakura.ne.jp
-- 生成時間: 2015 年 10 月 13 日 21:44
-- サーバのバージョン: 5.5.38
-- PHP のバージョン: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- データベース: `ryomakonno_carryjapan`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` int(4) NOT NULL,
  `group` int(2) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- テーブルのデータをダンプしています `options`
--

INSERT INTO `options` (`id`, `name`, `price`, `group`, `created`, `modified`) VALUES
(1, '強化梱包', 500, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '禁制品', 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, '謎のオプション', 2000, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `weight` int(7) NOT NULL,
  `is_bundled` int(1) NOT NULL DEFAULT '0' COMMENT '0: 同梱処理中, 1: 同梱済み',
  `has_paid` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- テーブルのデータをダンプしています `packages`
--

INSERT INTO `packages` (`id`, `user_id`, `weight`, `is_bundled`, `has_paid`, `created`, `modified`) VALUES
(1, 7, 2111, 1, 1, '2015-07-28 20:11:10', '2015-07-28 23:21:27'),
(2, 11, 496380, 1, 1, '2015-07-28 20:33:13', '2015-07-28 20:36:07'),
(3, 7, 0, 0, 0, '2015-07-30 19:16:51', '2015-07-30 19:16:51'),
(4, 7, 0, 0, 1, '2015-08-31 10:40:05', '2015-08-31 10:40:05');

-- --------------------------------------------------------

--
-- テーブルの構造 `package_categories`
--

CREATE TABLE IF NOT EXISTS `package_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータをダンプしています `package_categories`
--

INSERT INTO `package_categories` (`id`, `name`, `created`, `modified`) VALUES
(1, '衣類', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '書籍', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `purchase_from`
--

CREATE TABLE IF NOT EXISTS `purchase_from` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- テーブルのデータをダンプしています `purchase_from`
--

INSERT INTO `purchase_from` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Amazon', '2015-07-07 00:00:00', '2015-07-07 00:00:00'),
(2, '楽天市場', '2015-07-07 12:35:57', '2015-07-07 12:36:01');

-- --------------------------------------------------------

--
-- テーブルの構造 `qrcodes`
--

CREATE TABLE IF NOT EXISTS `qrcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `purchase_from_id` int(11) NOT NULL,
  `package_category_id` int(11) NOT NULL,
  `tracking_number` int(5) NOT NULL,
  `weight` int(6) NOT NULL,
  `price` int(6) NOT NULL,
  `is_combine` int(1) NOT NULL DEFAULT '0' COMMENT '0: 同梱可能, 1: 同梱不可',
  `is_packaged` int(1) NOT NULL DEFAULT '0' COMMENT '0: 発送依頼を受けていない, 1: 発送依頼を受けPackageを生成済み',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- テーブルのデータをダンプしています `qrcodes`
--

INSERT INTO `qrcodes` (`id`, `unique_id`, `user_id`, `package_id`, `purchase_from_id`, `package_category_id`, `tracking_number`, `weight`, `price`, `is_combine`, `is_packaged`, `created`, `modified`) VALUES
(1, 1, 7, 1, 1, 1, 11111, 1000, 111, 0, 1, '2015-07-28 20:09:19', '2015-07-28 20:09:29'),
(2, 2, 7, 1, 1, 1, 11111, 1111, 111, 0, 1, '2015-07-28 20:09:45', '2015-07-28 20:09:53'),
(4, 2456, 11, 2, 1, 1, 12232, 42424, 12334, 0, 1, '2015-07-28 20:30:27', '2015-07-28 20:30:46'),
(5, 0, 11, 2, 1, 1, 12323, 421421, 241244, 0, 1, '2015-07-28 20:31:18', '2015-07-28 20:31:27'),
(6, 3566, 11, 2, 1, 1, 22445, 32535, 35535, 0, 1, '2015-07-28 20:32:09', '2015-07-28 20:32:16'),
(7, 10, 7, 3, 1, 1, 11111, 11111, 11111, 0, 1, '2015-07-30 19:14:35', '2015-07-30 19:14:42'),
(8, 11, 7, 3, 1, 1, 11111, 11111, 11111, 0, 1, '2015-07-30 19:14:50', '2015-07-30 19:14:57'),
(9, 12, 7, 4, 1, 1, 11111, 1111, 1111, 0, 1, '2015-07-30 19:15:19', '2015-07-30 19:15:25');

-- --------------------------------------------------------

--
-- テーブルの構造 `receipts`
--

CREATE TABLE IF NOT EXISTS `receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `amount` int(7) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- テーブルのデータをダンプしています `receipts`
--


-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `webpay_code` varchar(30) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- テーブルのデータをダンプしています `users`
--

INSERT INTO `users` (`id`, `unique_id`, `email`, `password`, `activation_code`, `is_active`, `webpay_code`, `created`, `modified`) VALUES
(7, 'AA01', 'zion.air1@gmail.com', '1c58c915fba5c931b86adf607ce7716d11e205a0', '2e94fbd7b35155448d44a9ab829fdd20', 1, 'cus_btzfM535A7RkaDI', '2015-06-02 20:19:21', '2015-07-20 20:56:28'),
(8, '8', 'wangsano@gmail.com', '282f6edfe2959eafdcf6112c4596761dffc5d7fd', '6b3e6c082e88f645b8e9e12745049543', 1, '', '2015-06-02 23:47:40', '2015-06-02 23:47:40'),
(9, '9', 'ryomeno5@gmail.com', '6c22a42f57942ecee44de5934a904f8a0f877479', 'd23521e4f03ec7be6efd2bf76d90f6c3', 1, '', '2015-06-02 23:50:14', '2015-06-02 23:50:14'),
(11, 'AAA0140', 'takayanagi@chapter8.jp', '733abca95c4d8750155f922994550c277b4e7821', 'ffd403f8393aaff0d36ef079c5bda1a8', 1, 'cus_bFXbLf0kZ0UW9AA', '2015-06-04 14:14:12', '2015-07-22 22:42:08'),
(17, 'AAA0153', 'ray.webworks@gmail.com', '46135b1217c5787f8e77cf036ff0d8a428bae2c0', '109767be012bc0a5356fe34c6c335b28', 1, '', '2015-06-18 20:38:30', '2015-06-20 20:07:59'),
(18, '', 'zion@gmail.com', '1c58c915fba5c931b86adf607ce7716d11e205a0', 'a8e8473518596fb19ffe54f36fdc5b08', 0, '', '2015-07-01 17:50:40', '2015-07-01 17:50:40'),
(19, 'AAA0171', 'takayanagi@growth-inc.com', '773bb2c658ded59fa37f695974f06e6e82dba6f5', 'ff24a34444c827061b44a9d41812adea', 1, '', '2015-07-12 16:37:54', '2015-07-12 16:37:54'),
(25, 'AAA0225', 'takano8810@yahoo.co.jp', '773bb2c658ded59fa37f695974f06e6e82dba6f5', '15cbec99dcddbcf15da1ea547cebee2e', 1, '', '2015-07-21 14:10:34', '2015-07-21 14:10:34');

-- --------------------------------------------------------

--
-- テーブルの構造 `user_addresses`
--

CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `country` varchar(20) NOT NULL,
  `address` varchar(256) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `tel_number` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- テーブルのデータをダンプしています `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `name`, `country`, `address`, `zipcode`, `tel_number`, `created`, `modified`) VALUES
(1, 7, 'RAY SMITH ', 'Israel', 'SOMEWHERE, CHIBA', '0193849', '0102034303940', '2015-07-18 13:26:54', '2015-07-18 13:26:54'),
(2, 7, 'ray smith', 'Bhutan', 'SOMEWHERE, CHIBA', '010200', '084094858403', '2015-07-18 15:48:03', '2015-07-18 15:48:03'),
(3, 11, '高谷朝子', 'Canada', 'うぃううぇいうｆほえうふえｗｈふぃえｗふぃおえｗふぃう３ｗ', '１２１２０４１８４', '２４１２４１３４１３４', '2015-07-22 22:41:28', '2015-07-22 22:41:28');
