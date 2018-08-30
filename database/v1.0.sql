
--
-- Estrutura para tabela `cb_documents`
--

CREATE TABLE `cb_documents` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE VIEW cb_documents_complete AS
SELECT
    `documents`.`id`,
    `documents`.`status`,
    `labels`.`id` as `label_id`,
    `labels`.`name` as `label_name`,
    `labels`.`parent` as `label_parent`,
    `labels`.`uri` as `label_uri`,
    `labels`.`status` as `label_status`,
    `files`.`id` as `file_id`,
    `files`.`name` as `file_name`,
    `files`.`description` as `file_description`,
    `files`.`uri` as `file_uri`,
    `files`.`extension` as `file_extension`,
    `files`.`size` as `file_size`,
    `files`.`views` as `file_views`,
    `files`.`mimetype` as `file_mimetype`,
    `files`.`date_hour` as `file_date_hour`,
    `files`.`metadata` as `file_metadata`,
    `files`.`status` as `file_status`
FROM `cb_documents` AS `documents`
INNER JOIN `cb_labels` AS `labels`
	ON `labels`.`id` = `documents`.`label_id`
LEFT JOIN `cb_files` AS `files`
	ON `files`.`id` = `documents`.`file_id`
