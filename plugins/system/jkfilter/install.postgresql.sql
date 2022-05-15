START TRANSACTION;

CREATE TABLE `#__jkfilter_content`(
    `ordering` INT(10) NOT NULL,
    `item_id` INT(10) NOT NULL,
    `field_id` INT(10) NOT NULL,
    `category_id` INT(10) NULL,
    `title` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `value` TEXT NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `field_template` VARCHAR(50) NOT NULL,
    `field_background` VARCHAR(50) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `context` VARCHAR(50) NOT NULL
) ENGINE = INNODB;

INSERT INTO `#__jkfilter_content` 
SELECT `fl`.`ordering`,`fv`.`item_id`,`fv`.`field_id`,`cn`.`catid` AS `category_id`,`fl`.`title`,
IFNULL(JSON_EXTRACT(`fl`.`fieldparams`,JSON_UNQUOTE(REPLACE(JSON_SEARCH(`fl`.`fieldparams`,'one',`fv`.`value`),'.value','.name'))),`fv`.`value`) as `name`,
`fv`.`value`,`fl`.`type`,
IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`fl`.`params`,'$.field_template')),'off') as `field_template`,
IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`fl`.`params`,'$.field_background')),'off') as `field_background`,
NOW() as `modified`,`fl`.`context`
FROM `#__fields` AS `fl`
LEFT JOIN `#__fields_values` AS `fv` ON `fv`.`field_id` = `fl`.`id`
LEFT JOIN `#__content` AS `cn` ON `cn`.`id` = `fv`.`item_id`
WHERE `fl`.`type` NOT IN('subform','media') AND `fl`.`state` = 1 AND fl.context = 'com_content.article' AND `fv`.`item_id` IS NOT NULL

/* CREATE TABLE `#__jkfilter_user`(
    `ordering` INT(10) NOT NULL,
    `item_id` INT(10) NOT NULL,
    `field_id` INT(10) NOT NULL,
    `category_id` INT(10) NULL,
    `title` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `value` TEXT NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `field_template` VARCHAR(50) NOT NULL,
    `field_background` VARCHAR(50) NOT NULL,
    `modified` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `context` VARCHAR(50) NOT NULL
) ENGINE = INNODB;

INSERT INTO `#__jkfilter_user` 
SELECT `fl`.`ordering`,`fv`.`item_id`,`fv`.`field_id`,`cn`.`catid` AS `category_id`,`fl`.`title`,
IFNULL(JSON_EXTRACT(`fl`.`fieldparams`,JSON_UNQUOTE(REPLACE(JSON_SEARCH(`fl`.`fieldparams`,'one',`fv`.`value`),'.value','.name'))),`fv`.`value`) as `name`,
`fv`.`value`,`fl`.`type`,
IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`fl`.`params`,'$.field_template')),'off') as `field_template`,
IFNULL(JSON_UNQUOTE(JSON_EXTRACT(`fl`.`params`,'$.field_background')),'off') as `field_background`,
NOW() as `modified`,`fl`.`context`
FROM `#__fields` AS `fl`
LEFT JOIN `#__fields_values` AS `fv` ON `fv`.`field_id` = `fl`.`id`
LEFT JOIN `#__content` AS `cn` ON `cn`.`id` = `fv`.`item_id`
WHERE `fl`.`type` NOT IN('subform','media') AND `fl`.`state` = 1 AND fl.context = 'com_users.user' AND `fv`.`item_id` IS NOT NULL */

COMMIT;