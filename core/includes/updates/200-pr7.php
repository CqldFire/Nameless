<?php
// 2.0.0 pr-7 to 2.0.0 pr-8 updater
try {
    $db_engine = Config::get('mysql/engine');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_engine || ($db_engine != 'MyISAM' && $db_engine != 'InnoDB'))
    $db_engine = 'InnoDB';

try {
    $db_charset = Config::get('mysql/charset');
} catch (Exception $e) {
    // unable to retrieve from config
    echo $e->getMessage() . '<br />';
}
if (!$db_charset || ($db_charset != 'utf8mb4' && $db_charset != 'latin1'))
    $db_charset = 'latin1';

// Edit Topics forum permission
try {
    $queries->alterTable('forum_permissions', '`edit_topic`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Custom pages basic setting
try {
    $queries->alterTable('custom_pages', '`basic`', "tinyint(1) NOT NULL DEFAULT '0'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Topic Updates 
try {
    $queries->alterTable('users', '`topic_updates`', "tinyint(1) NOT NULL DEFAULT '1'");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Discord Integration
try {
    $queries->create('settings', array(
        'name' => 'discord_integration',
        'value' => 0,
    ));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->create('settings', array(
        'name' => 'discord_bot_url',
        'value' => 'https://bot.tadhgboyle.dev'
    ));
    $queries->create('settings', array(
        'name' => 'discord_bot_url_backup',
        'value' => 'http://bot-2.namelessmc.com'
    ));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('groups', '`discord_role_id`', "bigint(18) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('users', '`discord_id`', "bigint(18) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Announcements

// Reset panel_sidebar cache so that the orders do not interfere on upgrade
try {
    unlink($cache->getCachePath() . '/f068dd5fa2de0ad75c7380f550e12fbb6d0ac70e.cache');
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    DB::getInstance()->query("CREATE TABLE `nl2_custom_announcements` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pages` varchar(1024) NOT NULL,
        `groups` varchar(1024) NOT NULL,
        `text_colour` varchar(7) NOT NULL,
        `background_colour` varchar(7) NOT NULL,
        `icon` varchar(64) NOT NULL,
        `closable` tinyint(1) NOT NULL DEFAULT '0',
        `header` varchar(64) NOT NULL,
        `message` varchar(1024) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=$db_engine DEFAULT CHARSET=$db_charset");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}

// Group Username Colour + Group CSS
try {
    DB::getInstance()->query("ALTER TABLE `nl2_groups` CHANGE `group_username_css` `group_username_color` VARCHAR(256) CHARACTER SET $db_charset NULL DEFAULT NULL;");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('groups', '`group_username_css`', "varchar(256) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}


// Multiple webhooks
try {
    $queries->alterTable('hooks', '`name`', "varchar(128) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->delete('settings', array('name', '=', 'forum_new_topic_hooks'));
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}
try {
    $queries->alterTable('forums', '`hooks`', "varchar(512) NULL DEFAULT NULL");
} catch (Exception $e) {
    echo $e->getMessage() . '<br />';
}