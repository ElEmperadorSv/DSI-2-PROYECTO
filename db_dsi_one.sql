-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-09-2023 a las 06:08:10
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_dsi_one`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_dsi`
--

CREATE TABLE `categorias_dsi` (
  `id_cat` int(3) NOT NULL,
  `nombre_cat` varchar(20) NOT NULL,
  `descripcion_cat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_dsi`
--

INSERT INTO `categorias_dsi` (`id_cat`, `nombre_cat`, `descripcion_cat`) VALUES
(1, 'Sala', 'Muebles de Sala'),
(2, 'Dormitorio', 'Muebles de dormitorio'),
(3, 'Comedor', 'Muebles de cocina'),
(4, 'Oficina', 'Muebles de oficina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_dsi`
--

CREATE TABLE `clientes_dsi` (
  `id_ct` int(11) NOT NULL,
  `dui_ct` int(11) NOT NULL,
  `nombre_ct` varchar(30) NOT NULL,
  `apellido_ct` varchar(30) NOT NULL,
  `nombre_completo_ct` varchar(60) NOT NULL,
  `fecha_nac_ct` date NOT NULL,
  `telefono_ct` int(8) NOT NULL,
  `email_ct` varchar(30) NOT NULL,
  `direccion_ct` varchar(50) NOT NULL,
  `estado_ct` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes_dsi`
--

INSERT INTO `clientes_dsi` (`id_ct`, `dui_ct`, `nombre_ct`, `apellido_ct`, `nombre_completo_ct`, `fecha_nac_ct`, `telefono_ct`, `email_ct`, `direccion_ct`, `estado_ct`) VALUES
(1, 123456789, 'Jorge', 'Lopez', 'Jorge Lopez', '2000-07-13', 68958360, 'jorgelopz@example.com', 'Barrio El Centro, Soyapango', 'ACTIVO'),
(2, 293060489, 'Antonio L.', 'Rivera', 'Antonio L. Rivera', '1998-04-17', 79958502, 'antonio@example.com', 'Barrio El Centro, Sonsonate', 'INACTIVO'),
(3, 293060488, 'Juan ', 'Lopez', 'Juan  Lopez', '2002-07-26', 68958502, 'juan@example.com', 'Barrio El Calvario, Sonsonate', 'ACTIVO'),
(4, 330604452, 'Dennys', 'Garcia', 'Dennys Garcia', '1999-06-30', 78958963, 'dennys@example.com', 'Miralvalle, San Salvador', 'ACTIVO'),
(5, 103060412, 'Luis', 'Moran', 'Luis Moran', '2002-05-29', 72694502, 'luismoran@example.com', 'Barrio El Calvario, La Libertad', 'ACTIVO'),
(6, 512060454, 'Juan ', 'Rivera', 'Juan  Rivera', '1998-08-14', 78950002, 'juanrive@example.com', 'Lolotique, San Miguel', 'ACTIVO'),
(7, 503012336, 'Maria', 'Torres', 'Maria Torres', '1998-04-10', 72958112, 'mariatorres@example.com', 'Barrio El Calvario, La Union', 'ACTIVO'),
(8, 896060461, 'Sarai', 'Guerrero', 'Sarai Guerrero', '1994-11-18', 71958566, 'sarai@example.com', 'Barrio El Centro, San Luis', 'ACTIVO'),
(9, 503130412, 'Veronica', 'Marmol', 'Veronica Marmol', '1995-12-29', 74958508, 'veronicam@example.com', 'Bosques Del Rio, Soyapango', 'ACTIVO'),
(10, 306090836, 'Mauri', 'Rivera', 'Mauri Rivera', '1997-11-20', 78609633, 'karenrivera@example.com', 'California, Usulutan', 'ACTIVO'),
(11, 561859556, 'Joel Alejandro', 'Garcia', 'Joel Alejandro Garcia', '2023-01-02', 75755050, 'joel@example.com', 'Colonia Bella Vista, Santa Ana', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_dsi`
--

CREATE TABLE `creditos_dsi` (
  `id` int(11) NOT NULL,
  `dui_ct` int(11) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  `num_credito` varchar(10) NOT NULL,
  `producto` varchar(30) NOT NULL,
  `cantidad_producto` int(4) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `interes` decimal(10,2) NOT NULL,
  `plazo` int(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `monto_pendiente` decimal(10,2) NOT NULL,
  `tipo_pago` enum('Mensual','','') NOT NULL DEFAULT 'Mensual',
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado_credito` enum('ACTIVO','FINALIZADO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos_dsi`
--

INSERT INTO `creditos_dsi` (`id`, `dui_ct`, `cliente`, `num_credito`, `producto`, `cantidad_producto`, `monto`, `interes`, `plazo`, `monto_total`, `monto_pendiente`, `tipo_pago`, `fecha_ini`, `fecha_fin`, `estado_credito`) VALUES
(8, 123456789, 'Jorge Lopez', 'DSI-00008', 'Ropero', 1, 100.00, 0.05, 1, 105.00, 105.00, 'Mensual', '2023-09-21', '2023-10-21', 'FINALIZADO');

--
-- Disparadores `creditos_dsi`
--
DELIMITER $$
CREATE TRIGGER `actualiza_estado_credito` BEFORE UPDATE ON `creditos_dsi` FOR EACH ROW BEGIN
    IF NEW.monto_pendiente = 0 THEN
        SET NEW.estado_credito = 'FINALIZADO';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_dsi`
--

CREATE TABLE `pagos_dsi` (
  `id_pago` int(11) NOT NULL,
  `num_credito` varchar(20) NOT NULL,
  `cuota` int(4) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` decimal(10,2) NOT NULL,
  `estado_pago` enum('PENDIENTE','REALIZADO','EN MORA') NOT NULL DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_dsi`
--

INSERT INTO `pagos_dsi` (`id_pago`, `num_credito`, `cuota`, `fecha_pago`, `monto_pago`, `estado_pago`) VALUES
(1, 'DSI-00008', 0, '2023-06-13', 116.57, ''),
(2, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(3, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(4, 'DSI-00008', 0, '2023-06-13', 116.67, ''),
(5, 'DSI-00008', 0, '2023-06-13', 103.00, ''),
(6, 'DSI-00008', 0, '2023-06-14', 262.50, ''),
(7, 'DSI-00008', 0, '2023-06-14', 262.50, ''),
(8, 'DSI-00008', 0, '2023-06-13', 262.50, ''),
(9, 'DSI-00008', 0, '2023-06-15', 62.50, ''),
(10, 'DSI-00008', 0, '2023-06-15', 25.75, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_dsi`
--

CREATE TABLE `productos_dsi` (
  `id_pd` int(4) NOT NULL,
  `nombre_pd` varchar(30) NOT NULL,
  `descripcion_pd` varchar(50) NOT NULL,
  `stock_pd` int(4) NOT NULL DEFAULT 1,
  `categoria_pd` varchar(10) NOT NULL,
  `precio_pd` decimal(6,0) NOT NULL,
  `estado_pd` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `imagen` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_dsi`
--

INSERT INTO `productos_dsi` (`id_pd`, `nombre_pd`, `descripcion_pd`, `stock_pd`, `categoria_pd`, `precio_pd`, `estado_pd`, `imagen`) VALUES
(1, 'Ropero', 'Guardar Ropa xd', 1, 'Dormitorio', 100, 'ACTIVO', 0xffd8ffe000104a46494600010100000100010000ffdb0084000a070812151218121512121818181818151a181812121a1a1118181a1919181818181c2330251c1e2b1f18182638262b2f313535351a243b403b343f2e343531010c0c0c100f101f12121f342b2121313a34343434313431343435343434343431343434343434343434343434343434343434313434343534343434343434343fffc000110800e100e103012200021101031101ffc4001c0000010501010100000000000000000000000304050607020108ffc40049100002010202030a09090704030101000001020003110421051231062241516171728191b11314233252a1b2c1d1071524344253738292334362a2b3c2e14493d2f01683e2f117ffc4001801010101010100000000000000000000000001020304ffc400221100020202020203010100000000000000000102112131031241511322326181ffda000c03010002110311003f00d9a1084008421002108400842100213c8403d84f2100f613c8403d8421002108400842100210840084210021084008421002108400848ec3e9bc2bf9988a4dcce0faa39f1ca5e9af6c01c4237f1ba7e9af6cf7c6a9fa6bdb005a111f1aa7e9af6c3c6a9fa6bdb005a111f1a4f4d7b61e334fd35ed802d088f8cd3f4d7b61e334fd35ed8b02d088f8d27a6bdb0f1ba7e9af6c58168443c6e9fa6bdb3cf1ca7e9af6c9607108dbc7697a6bdb0f1fa5f78bdb16850e611afce14bef13b61f3851fbc5ed8b4287508d7e71a3f78bdb0f9c28fde2f6c5a143a846bf3851fbc4ed87ce147ef13b62d1687508d7e70a3f789db0f9c28fde276c5a143a846bf3851fbc4ed87ce147ef13b62d01d4235f9c68fde2f6cf3e71a3f789faa2d1287708d3e73a1f7a9fa8422d0a32dc09414c2b1033277ebbdbf059f80c934c5d44d8c6dc4f760799c6639cde2385c0eb53d7e717e2ebcadd6cbcc6235308e9e6dc5f806c6eab59ba95b9e61ecd2d135474ca7db05394e6a7f38c875da3f5c4822e0de54a93bb161aa0116b95c8e640cd731c3c60f2487c6692ab4f24b236b1cc6c205b6a1c8defb6c36415e0d1fc3c3c3ccef0fbb2a837af4ae78d5ac0f51d91d0dd6d43b280eba9ff00ccb466d17bf1830f18328bff0093d63b29d31ce58fc270774d88e3a23a8fbda4669345e9b14d3838a6e39456dd1573fbda639953df78d9f4e563fea3b153dcb32dff004b9f4680712dc73c35cf1ccddb4b546ff5153a8b8ee1126c631db56a9fceff00193b2f65a7e8d2cd6e589362546d751cec266a5c1da5cf3b7c4cf1557d18ecbd97acbd1a2be91a236d5a63ff00627c624fa670c3f7d4fa9afdd280081f6275affc2bd67dd2765ecaa32f45d9b4fe187ef2fcc8e7dd136dd2e1f80b9e6a6def94c351b897b3fcc4dab3f1a8fca3e327743a48b936ea28f053aa7f2a0ef689b6ea93828d4eb651dd7952152a1d841e61f013a6a15ec5b52b58024914dec00da49b6423ba1d1fb45a1b74edc143b6a7ff003136dd2d5e0a4839d98fc240e8ac58a89636d75da38c70308ffc14e8a9e51ce4da74c76dba1c49d8b4c7e56ff944db4de2cfdb41cc83df10f053dd4968cf667ada57167f7c7a9698f7449b1d893b6b54ea6b774eca4f352516c41aad53b6ad43ff00b1fe3136463b598f3b131d149c94820d3c008473ab0821a0687a7e494f116cf8b3e3e0ed58e2b505b136163b4e563ce6f63d65e71a1c7925e42dcb6cf98dbf963aabb09f583dec0f7b8868dad10c98701d85bec8cb3d9acbb01b65cca073caa69bc180bae3819b8b84a8e0cbdf2e9486f9b88db989d65e100293d6c79656f740be4cf39db7e35e3cfd4042d924f053453df88fa9538881bf8fe92cd1cce93473d6b524d5d7721575890b7246d201b0ea92547e4e319f6aa6147354aa7fb045773c3e954ba69de26a5394926f277836960ccd3e4e2b819e2288e6476efb4749f26e7ed62d7aa81efd79a054d9d9df3a995046bbb2887e4ea9aa92710e6c09ca9a0d8394998f56d355149528a39af3e99ade63744f719f2fe3e95f391a8a6951b836d314f9eaa7010270da5aa9fb5ea8de952013588ecdbb6d27f466e6ea56a6b514d3556bd8316be448cc0078b8e14537491aba56c863a46afa666cff00269a2f0f5b46d3ab5a8d3772d52ece8ac480ec06de412849b8da9c2f4fb18cd67709833470294c907559f302d7bb13b3ae6d45ada312926b0c944d0d855f370d871cd469fc2763094d6a295a74c0d47d88a3852db07218ee727ce1cc7dd2d1ccf465b232d37f55aff8353d868f633d37f55aff008353d8691e82302d09fb43d1f789674959d0bfb43d1f78967499e3d1ae5d9d813dd58a22c5024e872a10d59c948e7560522c50d4ac4d963975883ca4625684ea1299b2ff00a205e92f0d8b705ed9f335bb047558df948eb23da23d51a689ce9a8db9b65b6d9f159adfa4473896cac7b0d8ff002b13ec4336b432a46eec769b0bec27ce1b4825bb58734ad69d20d3cbd26bdb576dd6fe6e5da4996543be20df60b037cb7c360639752095cd3d7d4cefb5b6eb6cbafa59db980122d925a2aaabbf92541232037f24b0e26999449e804fa552e9af789a6cce3418fa452e9a7789a3ce6f6748e8e6a6cecef9d4e6a6cecef9d488a715bcc6e89ee9f34e21723cdef9f4b56f31ba27ba7cd55787fef0ce5c9b476e2f27829f91bf37b424de8bc7d74a6a885428bdb7a09cc9264695fa3f67b525345a5e9af5f799be07f6273e2381ea63f147f796e644f84d4370cee70485db59b5dee6c05f7c6db39266f4d2695b8c16c1af4dfda9e89e8f241b6c9e9c9da398fba7b393e70e63ee9c8ec7518e9bfaad7fc1a9ec347d18e9cfaa57fc1a9ec348f454603a0dfca1e8ff0070969a4f2a3a00f946e8fbc4b421882c1795e493a66395591b4ea5a3a4c409a68e69a1c9489d49e362446f52b5e14586d1e5468dd8ce98ce0cda4736cf21084a42f9a2b3a6a36e6d964787d13adecc73893616390e23751d84a0fe58df441f24a2fc2d95f978b5bfb638ac840c85b9815f5aaa77c8755a1a61d6d7b0b0cb60b0f387a2aabeb32b5a6ed6cadb4ecd4db7e1d51b79c932cd4c0b9d97cbd12768e1bb9f5cade9ebe57d6e1dbafc63d23dc0093c925a2b8a37f2428c60be7c7f4e566099d067e914ba69de268f337d067e914ba69de269139bd9d63a39a9b3b3be75387d9d9de2773268e2b798dd13dd3e6bab3e93afe63745bb8cf9b1b68eaff139726d1d78bc8e8af90b728f6af25f450f2639dbda323e9283445f9f21b6d6caddb1e602a591010732dd5624e735c2ea4679f31ff499a7345dc77d517a6fdf337a4f97abaf8668fb8dfa9af4dfda9e99e8f2f1ec9d9c9f38731ef59d4e4f9c398f7ace56763a8c34f7d5311f8357fa6d1fc61a77ea95ff0006aff4da46547cf5a00f946e8fbc4b429954d007ca3747de25990cd434397f47b8ac68a7ab70496bdad6e0b6ded8ddf4cdbf77fcff00e236d26d7a88bc42fda7fc46358e6656dd9951544ed3d2848bea0fd47e115c263f5dd9356c40076dee0e52270be64eb0afa95afc6847aefee88b76251544fde1114ac0c5034ea713db42178402f7a19fc928bf0b70f2f16b7ba3aacbc43b17de13df19e86a9e4973b66df6adfde3ba3aac41e23fa4ff00ca64eab4226f737bf06d2dc63d27b7aa5574ddb82dc3b3c1f18f4477932d76db60783606e31c48255f7417cafadc3b7c2718f48f7093c925a2ba3cf8f9231077f1ea4d1825f411fa452e9a7b42695333d067e934ba69ed09a64e52d9d63a13ad5545816504e60122e6c45ec392748eac0329041cc104104721905a72de141e15c3d561c875a98ee927a2502d0a606c089ec898bcd1ba1cd7f31ba2ddc67cd55765f9a7d2b88f31ba2ddc67cd358ef7b2739ed1d78bc930946f4036b67aa4ece4278f9623843ada94c13ac4d80e3275883dbb79a3d51e4542f0a039f195b7798d3014992ad3620596e4e7986be5ea1ebe4960d4646671728b2ca6894505b8b82e76587bc76cd0b718d7c1a9cfcf7da08fb5c4645be1283e1cd465174a28ea41236d3462481e766a36f2f1c93dc4d40d84561b0bd4b753113bb95e0e318564b0464fa46906cdc6f58d3391c9c94b2fac4792a9588d63cb8b20f53a09ce4e8da45ae30dd07d5311f8157fa6d1fac61ba0faa623f02aff004da522d9f3ae813e51ba3ef12cea6563408f28dd1f7896741371d1397f447d537c4732dbd5fe632ade71e731e51b9ace6c7691d840f746c70d50dc8a750f3237c24bc84b087784f32235cd9d0f291db1d61f0d5156cd4dd4dced461de236c60391b6c60644f256b03da1523e4791b4c47b4ccea8e2d0e6f089de1290be688ab6a437d6b16fb56fef1dd1d3540786ff009d4f0fe218db432f9119919b7da61dcc23c0878598fe7a9c9fc6665bc9d62b0240ae7e6f070d3e31ca655b4edae2dabc3b3c1f18f4477996d37cf36e0e17e3e94aa6e8af95f5b876f84e31b358f7427924960af0f3faa3ca71929dff005478a6699c896d067e9347f113da134d996e833f4aa3f889ed09a8ce4f6768e88ad29a39dea2d40e02847a6c2de76b94b5b98ac79a2f0ef4e8a23b0664455246c240b5e2f536758ef13b98acd9ab39c4798dd16ee33e5fc662196d6084586d17f7cfa7ebf98dd16ee33e73d1181a55f19428d56d547601cdec48009d50780b5b57f348ff0048e90fcb238e96c52850ca1548056f4c80cbc045f689c7cf35f6ef3f409aff00caa68fc23e8f1514aabe1b5569dac06a92a869db8ad62388a8e599eee67736b882cb5531280aab254186acc8d726e6e2c00b6adafb73e49d1a5e89178d91bff9663f54a7863aa54215b64540b016e2b65369f92aaacda2e9b36d2f5783f8d84cf3741b8ca342906a671355b2de261aa16626c0b16cc2802e6c79af345f92da0e9a329a3a3a307abbd75656177622ea73191063c688daf05b8cafd7d0b50bb5aa01ad5bc3aef7cd01a992bb791b3e596089b79e3a2dde932d266533b123f743f53c47e055fe9b49091fba0faa623f02aff4da023e78d05fb43d1f78968a6257b4351b54fcbef12d986a12c6582f2ac9c25327608e30fa3df7fc04a301d6547713242820023857da798765c9ef123933318919a6f46355a82a023658df8f599bb98486aba3ea26d1d92daf54dc8b0b0d437be7be45cadf94c6b588333d9c5d7837d53c9570b6317a71fe22808d0d3b4eb1959ca51a3dbc279684dd98a347dcf213405afe73701e39e3e90a20ea9a8b7d655b5c93ac760caf21f0dba2f034e9d0555bb3312ed529aad3df0201526f98e1b5b3918f8aa0d5f528a54776280b35750baea028b1406fb00b8338724e9e0f47143b2c96da9529ea9375daabb33cc8b0f365434cd647b94fb24a9de019dc0f445e58de935937802d9331c2e5c5c1d97b71da55b1ea02d4e9b7b72f0c9c95b33cf1517488753bf8f11a3153bfea8ed4cee79c95d06df4aa3f8a9ed09a94ca3419fa551fc54f684d5819ca5b3b4741536758ef13a89d4d9d63bc4ee64d1c624f937e8b7719f3fa504205e98cadc02feb9bee38f927e83fb2660c8ee4657ec9cb93c1db8bc8b57735001501700dc0725803b3206f9c714749e250045ab511546aaaad47b0032000d805b8247f8c58d89707f0ee3a8de78f8ab641d89e2f0673e737ca73b674a4493e99c4f0d6affee37c669db80aacf8146666625ea5cb124f9e7699918a8fb48ee9acfc9e35f0087f8ea7b4674836e463912512cf136f3d7a2fde93b89b7ed17a2fde93a9c05230d3ff0054c47e055fe9b47f23f7427e8788fc0abfd36808c27426753f29f684b5d236952d02de50f47de25a11e660b06f9763e579d33e43acfaedfda2350f3b76e0e200760cfd7354734c77886cdba14dbb1557fbfba32778bd67df0fe2a4076286fec96adca5256c2d9d55833b1b328232b2f0f318eb6cd2782946e4d80249d800b93cc22a344e21b651a9d6a477cd0a9e8cc3a36ba53456b117516dbc8329dba20fff0063ab5a263c99d7cc989fb96ed4f8c25fee9c47b4c26bec3ea657a7b0f8700d565aaf5192c02b6f295895577b0cb3d809cfaa38c26328d1408daf714d42b8c8537a84396b379c7548caf9db836c474955a85d513c26a80359570f52a254df6b0f09abb40205867b0c8ad2a989ad507917660806aa61aa0214120165cc826dc3c169e7971ca72a7ab6768f246114d6e917ed0ba455c1a7e103b2ba36b8bd8aeba0e219dcc88d2477b53a67db9cee370b569bb6bd3a897d4f3e9badfca26cb886923bda9d33edcefc30505d51e7e69b9bb64229dff5474ad1a29dff00547179d8e249e826fa5d0fc54f684d601990e806fa650fc54f684d6c19ca5b3b4747b50e5d63bc456f10a872eb1de22b79934258efd93f41fd933074416f39fb66ef8e3e49fa0fec99802b9b6cbe5fc33972783b71791764bfa7cfabefb4e569119ddc73a8cbd51b14cefbf1ccc3baf3d2a4917353f56df5ce6741e67e931ea4f789acfc9dfd413a7538bd23c532315391bfef5cd6be4ecfd013a753db337c7b31c9f92d1126fda2f41fbe9c5224dfb45e83f7d39d8e02b23b745f53c4fe056fe9b491bc8ddd09fa1e27f02aff004da0a8c0f4137943d1f7896946952d06de50f47de259a9b44560bcbb1fd1cd80e3201e6e183bdee78cde2545b69e204f6ef7fb84f0b4dd64e7781d54376419e61172241cceaed1342d1986f034529dee55733c64e67d64ca8ee7b081dd2abdb529a839fda7d66d51d5b7a84b5362976dc76c043b678cf175828b939483c769f2495a66c366b5ae5b9b88487c4d4673be72794927df945968b1fce54bd313d94ff014fd2308297ea2951732699bdb7a2ea17d2cf3be5cdb2755ea05db6b8b70c6d88ae000410769b820836e5eb8962aec8757325030cf6119e5cf9891af4131c57c52eae601b1073e3198328fa5466ea06d7b81d221adeb96141508cd7b488f42aead88626dc032eaca484b2271b467b4f47d62d714dfb2ddf167c0d61fbba9fa4cb9b0371aa96b71b5afd4276ce7d1fe633a7639f429ba2c1a588a552a2b2223a3bbb2b0545045c936c84d0d3759a38ffadc375d541df2b1ba1a9f45abbc3e63708f8cce69d10f92a5c817b0bdedc7394e54f47784156cdc4ee9302c37b8cc21cc6cc452e31cb1e2697c2b6cc4e1cf356a67df300b5306c7541195891713df1643c03d531dff0086fe25ecdff158ca6693daa21de3ec743f64f11986aa25b68f54647094f897b07c27a306873013b2624d48d463d45ddecdfb3b8e30c41ecd93c67171643fab6755b388f8927a2bd5780c12f2f6998c1aa63e645397c7dd358f93b16c0201e9d4f6ccc68e10703b7ea68fb058fc5525d4a78bc422824855aae05cedcaf3516a2ccc9392a37f88b1f28bd07f6926203745a486cc757eb643de2763751a4c10de3950900804d3a07236bed5e41d93a77898f899b8c8ddd11fa1e27f02b7f4da64cbbb2d283fd593d2a187f724f311bb4d22e8f49eaa1475646f2080eab02ad622d636263ba27c722ada11bca1e8fbc4b2a3484dc960455aaeb722c80e5cac25e296e773075efc85723c87313a2748cf226e44a60f732babbfad9902e11465c245cedcedc1c11da6e630fc2f50fe651dcb182d0ae09d5a87236b6badbb185ff9a2786d29598b052ae50d9b7ac2db784137d8764e4f96b768d7c4bc513a9a1e9aa8456a8146c01c709bf14f1f44af054a9da87fb645ae9ba80d9905f86ccbdcd63175d3c3855c7e46b768ca17227e4746bc11fa43733545da85451c3aac0db8f2b6cffbb240e9435e9828f4dc3301e60d7078cdc676becc84b9269ea472d75bf38bc54e954b5ee26d489d4cc3c257f46a7fb6ff0009e4d2be785e2f5cf65ec4a630c362948208297392b5803cc465792f8663aa178734f78ef95c7a81e99045c11c513dcc552159b5c90ae518162752cc59323b37ac0088cac4a24e2e2811b73e29d54c5ba8b051c79f2c58d5a61c8de837bec1f686b7be3aa3a3e9d46d7724f06a836d9ca222b224f042f8cd42783982c7d86c1e21f32028fe21abeab13ea961a385a6992a01cd7f59db1714c1e0b759f7de6fa98b20aa6e752a2353aaee4302085b0163b45ed7ee91f4b70384424a1aa0916f3af9647879a5bfc181c17eb3387a77cee472714bd512d944c47c9960d98b17a9726f73ae6e4fe789bfc9bd2e0aedd6bffd4be8a7c59c5147f08ecbde2916d99bb7c9b0e0afda2dee3107f936a9f66b2f6b0fed9a5306ff00bfe6014ec361cf27543b332e7f93bc48f36b533ce58fba36adf2798e61955a6bca0fc489ad6a5bed5fb8fae26dae762d8f318ea8bd999226e0748adef50375afc4c1f715a486c09d67fc4d7901b666fcd140471769b49d5053662cdb93d283f76a79b5ff00e3386dcee911b681ea07e026d2c39c72d8dbb67258017249eb93a47d1af919883687c729ce83fabbaf12a984c40c9a8d41f90cdd03b1d96eff007ce8f2dbae4f8e2553663bb90d0ef4ddd8eb82c0717013616b72cbad346f49bf97e12ccc8a73083ac09e1a29b4aa7300b2f52762b28eeb4d98b5ac5c9d9c6673a2b0cf4e9ed00b1d76054dc13b01cf805847da61d14f980220351ae0aab91e6a1206776b6c07d72ad5b4d54b96b36dbd81701793693eb9ce9f6b7e308d5aeb5ecb1d32de11f35cc21396dc88e38d8d1df674e99de8cc657b139e59826f21869d237cc8492382e36123618955d37b0ef85c5f60cb68ef134d2644e87fa5699434ed60af515186b16f3f679c32cc46788c25ab8a4aa801172c15411913f640e29198ed285c01e11fcf4398196ab037f5491ad89d5a74ea1a99b06df11b45c8d9cc2709c526a96da3ac658cfa63af998fa7eb7ff9c247fce67ef57b0c275f8d18ee6b3ff8de1ad6d46fd6d1a53dc66095daa2a540cde75aad4b3738bda58e13d148f3db21aaee6b0ac759a99272cf5ea026c2c2f63c51e51d19490595480394c7b09690b62030cbcbda678d83439e7da63884a410f144e5ed9e1c2272f6c7108036f124e23db3df134e5ed31cc24a0363844e23db3cf124fe21ccc447508a036f134f47d667a3089c5eb8e2114040e1938bd667830abcbfa8c7108a0373854e29c78853f47d71dc2280d7c49388f6ce7c429f11fd463c84516c6634753e23da679f36d3f44fea31e4229108cc5684a151755d588b83e730d9b2336dc860cfeedbfdc7f8c9f84522db2bedb8ec11db4dbfdc7f8c42a6e1b47b6da4dc5fb5a9f1967845216ca9b7c9f68d3fbba9fef54f8c5ab6e1f00ea88c8f641651e16a0b0edce59a12755e876654bffe79a37eeea7fbd53e33d96c84b485b3d84212902108400842100210840084210021084008421002108400842100210840084210021084008421002108403c84f61002108403c9ec2100210840084210021084008421002108403c8421002108400842100210840084210021084008421002108403ffd9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
  `id` int(3) NOT NULL,
  `nombre_rol` varchar(25) NOT NULL,
  `descripcion_rol` varchar(50) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_usuarios`
--

INSERT INTO `roles_usuarios` (`id`, `nombre_rol`, `descripcion_rol`, `estado`) VALUES
(1, 'Administrador', 'prueba', 'ACTIVO'),
(2, 'Depto. de Contabilidad', 'Rol que permite ver el detalle de créditos, produc', 'ACTIVO'),
(3, 'Cajero', 'Gestiona los créditos, usuarios, etc', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_dsi`
--

CREATE TABLE `usuarios_dsi` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(25) NOT NULL,
  `carne` varchar(10) NOT NULL,
  `email` varchar(25) NOT NULL,
  `codigo_recuperacion` varchar(6) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `rol` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_dsi`
--

INSERT INTO `usuarios_dsi` (`id`, `username`, `password`, `carne`, `email`, `codigo_recuperacion`, `estado`, `rol`) VALUES
(1, 'admin', 'admin', 'ADMIN', 'admin@admin', NULL, 'ACTIVO', 'Administrador'),
(2, 'hgarcia', 'AG00026', 'GB15026', 'gb15026@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(3, 'rongonza', 'AG00026', 'AG00026', 'ag00026@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(4, 'jenherrera', 'HR08032', 'HR08032', 'hr08032@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(5, 'dangarcia', 'GT19011', 'GT19011', 'gt19011@ues.edu.sv', NULL, 'ACTIVO', 'Administrador'),
(7, 'ejemplo', 'ejemplo', 'pruebe', 'ejemplo@gmail.com', NULL, 'ACTIVO', 'Cajero');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_dsi`
--
ALTER TABLE `categorias_dsi`
  ADD PRIMARY KEY (`id_cat`),
  ADD KEY `nombre_cat` (`nombre_cat`);

--
-- Indices de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  ADD PRIMARY KEY (`id_ct`),
  ADD UNIQUE KEY `telefono` (`telefono_ct`),
  ADD UNIQUE KEY `email` (`email_ct`),
  ADD UNIQUE KEY `dui_ct` (`dui_ct`),
  ADD KEY `nombre_completo_ct` (`nombre_completo_ct`),
  ADD KEY `estado_ct` (`estado_ct`),
  ADD KEY `dui_ct_2` (`dui_ct`);

--
-- Indices de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_num_credito` (`num_credito`),
  ADD KEY `producto` (`producto`),
  ADD KEY `cantidad_producto` (`cantidad_producto`),
  ADD KEY `monto` (`monto`),
  ADD KEY `plazo` (`plazo`),
  ADD KEY `monto_total` (`monto_total`),
  ADD KEY `monto_pendiente` (`monto_pendiente`),
  ADD KEY `tipo_pago` (`tipo_pago`),
  ADD KEY `fecha_ini` (`fecha_ini`),
  ADD KEY `fecha_fin` (`fecha_fin`),
  ADD KEY `estado_credito` (`estado_credito`),
  ADD KEY `cliente` (`cliente`),
  ADD KEY `dui_ct` (`dui_ct`);

--
-- Indices de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `num_credito` (`num_credito`),
  ADD KEY `cuota` (`cuota`),
  ADD KEY `fecha_pago` (`fecha_pago`),
  ADD KEY `monto_pago` (`monto_pago`),
  ADD KEY `estado_pago` (`estado_pago`);

--
-- Indices de la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  ADD PRIMARY KEY (`id_pd`),
  ADD KEY `nombre_pd` (`nombre_pd`),
  ADD KEY `stock_pd` (`stock_pd`),
  ADD KEY `categoria_pd` (`categoria_pd`),
  ADD KEY `precio_pd` (`precio_pd`),
  ADD KEY `estado_pd` (`estado_pd`);

--
-- Indices de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombre_rol` (`nombre_rol`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carne` (`carne`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_1` (`rol`) USING BTREE,
  ADD KEY `estado` (`estado`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_dsi`
--
ALTER TABLE `categorias_dsi`
  MODIFY `id_cat` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes_dsi`
--
ALTER TABLE `clientes_dsi`
  MODIFY `id_ct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  MODIFY `id_pd` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `creditos_dsi`
--
ALTER TABLE `creditos_dsi`
  ADD CONSTRAINT `creditos_dsi_ibfk_1` FOREIGN KEY (`producto`) REFERENCES `productos_dsi` (`nombre_pd`),
  ADD CONSTRAINT `creditos_dsi_ibfk_2` FOREIGN KEY (`dui_ct`) REFERENCES `clientes_dsi` (`dui_ct`),
  ADD CONSTRAINT `creditos_dsi_ibfk_3` FOREIGN KEY (`cliente`) REFERENCES `clientes_dsi` (`nombre_completo_ct`);

--
-- Filtros para la tabla `pagos_dsi`
--
ALTER TABLE `pagos_dsi`
  ADD CONSTRAINT `pagos_dsi_ibfk_1` FOREIGN KEY (`num_credito`) REFERENCES `creditos_dsi` (`num_credito`);

--
-- Filtros para la tabla `productos_dsi`
--
ALTER TABLE `productos_dsi`
  ADD CONSTRAINT `productos_dsi_ibfk_1` FOREIGN KEY (`categoria_pd`) REFERENCES `categorias_dsi` (`nombre_cat`);

--
-- Filtros para la tabla `usuarios_dsi`
--
ALTER TABLE `usuarios_dsi`
  ADD CONSTRAINT `usuarios_dsi_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles_usuarios` (`nombre_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
