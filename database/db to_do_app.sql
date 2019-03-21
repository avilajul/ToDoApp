 -- Crear BD 
 CREATE DATABASE to_do_app;
--
-- COPIAR A LA BASE DE DATOS después de Realizar las migraciones

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `condicion`) VALUES
(NULL, 'Usuario', 'Usuario de la aplicación', 1);

INSERT INTO `personas` (`id`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES 
(1, 'Julian Avila', 'CC', '14798947', 'calle 24 # 35-67', '3177893544', 'avilatas@gmail.com', NULL, NULL);

INSERT INTO `users` (`id`, `usuario`, `password`, `condicion`, `id_rol`, `remember_token`) VALUES 
(1, 'jeat', '$2y$12$iicnyde.EBiJbB9GjjJ20eWDEIpx3YTX8F8FBW39CofKwNpVL1y/i', 1, 1, 'jEv7lKMBimkVMWw5G19o2a4CKn8a2ZTUgNiA23hqiAuajwp1aQouCqOUC3Cs');

INSERT INTO `categorias` (`id`, `nombre`, `condicion`, `created_at`, `updated_at`) VALUES
(NULL, 'Entretenimiento', 1, NULL, NULL),
(NULL, 'Trabajo', 1, NULL, NULL),
(NULL, 'Salud', 1, NULL, NULL),
(NULL, 'Viajes', 1, NULL, NULL),
(NULL, 'Estudio', 1, NULL, NULL),
(NULL, 'Compras', 1, NULL, NULL);