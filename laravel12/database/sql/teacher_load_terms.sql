CREATE TABLE IF NOT EXISTS `teacher_load_terms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_load_id` BIGINT UNSIGNED NOT NULL,
  `term_no` TINYINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_load_terms_teacher_load_id_term_no_unique` (`teacher_load_id`, `term_no`),
  KEY `teacher_load_terms_term_no_index` (`term_no`),
  CONSTRAINT `teacher_load_terms_teacher_load_id_foreign`
    FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `teacher_load_terms` (`teacher_load_id`, `term_no`, `created_at`, `updated_at`)
SELECT `id`, 1, NOW(), NOW() FROM `teacher_loads`;

INSERT IGNORE INTO `teacher_load_terms` (`teacher_load_id`, `term_no`, `created_at`, `updated_at`)
SELECT `id`, 2, NOW(), NOW() FROM `teacher_loads`;

INSERT IGNORE INTO `teacher_load_terms` (`teacher_load_id`, `term_no`, `created_at`, `updated_at`)
SELECT `id`, 3, NOW(), NOW() FROM `teacher_loads`;
