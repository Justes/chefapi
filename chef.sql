
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` bigint(20) unsigned DEFAULT '0' COMMENT '父级分类ID',
  `parent_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '父类名',
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `active` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)COMMENT='产品分类';