SET NAMES utf8mb4;

DROP TABLE IF EXISTS `admin_menus`;

CREATE TABLE `admin_menus` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `type` int(11) DEFAULT '0',
   `parent_id` int(11) NOT NULL DEFAULT '0',
   `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
   `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `sort` int(11) NOT NULL DEFAULT '0',
   `created_at` timestamp NULL DEFAULT NULL,
   `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menus` WRITE;

INSERT INTO `admin_menus` (`id`, `type`, `parent_id`, `title`, `slug`, `icon`, `uri`, `sort`, `created_at`, `updated_at`)
VALUES
    (1,1,0,'首页','home','fa-dashboard','/',1,'2024-04-17 12:23:01','2024-04-26 12:56:50'),
    (2,1,0,'系统设置','system','fa-tasks','',3,'2023-05-21 16:35:31','2023-05-21 16:35:31'),
    (3,1,2,'用户管理','user','fa-user','users',4,'2024-04-16 20:02:22','2024-04-16 20:02:22'),
    (4,1,2,'角色管理','role','fa-user-md','roles',3,'2024-04-16 19:43:50','2024-04-16 19:43:50'),
    (6,1,2,'菜单模块','menu','fa-bars','menu',1,'2024-04-17 12:22:12','2024-04-17 12:22:12'),
    (7,2,6,'新增菜单','menu_create','','',1,'2024-04-19 13:34:24','2024-04-19 13:34:24'),
    (8,2,6,'编辑菜单','menu_edit','','',2,'2024-04-19 13:34:33','2024-04-19 13:34:33'),
    (9,2,6,'删除菜单','menu_delete','','',3,'2024-04-17 09:13:43','2024-04-17 09:13:43'),
    (10,2,4,'新增角色','role_create','','',1,'2024-04-17 12:23:22','2024-04-17 12:23:22'),
    (11,2,4,'编辑角色','role_edit','','',2,'2024-04-17 12:23:41','2024-04-17 12:23:41'),
    (12,2,4,'删除角色','role_delete','','',3,'2024-04-17 12:23:56','2024-04-17 12:23:56'),
    (13,2,3,'新增用户','user_create','','',1,'2024-04-17 12:24:12','2024-04-17 12:24:12'),
    (14,2,3,'编辑用户','user_edit','','',2,'2024-04-17 12:24:34','2024-04-17 12:24:34'),
    (15,2,3,'删除用户','user_delete','','',3,'2024-04-17 12:24:49','2024-04-17 12:24:49'),
    (19,1,0,'系统工具','system','fa-gears','#',20,'2024-04-27 10:38:36','2024-04-27 10:38:36'),
    (20,1,19,'脚手架','scaffold','fa-keyboard-o','scaffold',1,'2024-04-27 10:39:40','2024-04-27 10:39:40');

UNLOCK TABLES;


DROP TABLE IF EXISTS `admin_role_permissions`;

CREATE TABLE `admin_role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `admin_role_users`;

CREATE TABLE `admin_role_users` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`role_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `admin_role_users_role_id_user_id_unique` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_users` WRITE;

INSERT INTO `admin_role_users` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`)
VALUES
    (1,1,1,'2024-04-25 08:38:41','2024-04-25 08:38:41');

UNLOCK TABLES;


DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
   `slug` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `created_at` timestamp NULL DEFAULT NULL,
   `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
    (1,'超级管理员','admin','2024-04-19 13:24:43','2024-04-19 13:24:43');

UNLOCK TABLES;

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `username` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
   `password` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT '',
   `salt` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '',
   `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `created_at` timestamp NULL DEFAULT NULL,
   `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;

INSERT INTO `admin_users` (`id`, `username`, `password`, `salt`, `name`, `avatar`, `created_at`, `updated_at`)
VALUES
    (1,'admin','e7cebef0d9206b446823f7ce09d8cb02','lhI5TZdZ','超级管理员','/uploads/images/5lICa69wBU.png','2023-05-19 09:17:43','2024-04-28 11:54:10');
UNLOCK TABLES;

CREATE TABLE `admin_stats` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `uri` text,
   `ip` varchar(20) DEFAULT NULL,
   `province` varchar(50) DEFAULT NULL,
   `city` varchar(50) DEFAULT NULL,
   `created_at` timestamp NULL DEFAULT NULL,
   `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
