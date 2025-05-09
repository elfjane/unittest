<?php
/**
 * CRON_BASE_PATH
 *
 * Dynamically figure out where in the filesystem we are located.
 * @global string WEB_BASE_PATH Absolute path to our framework
 */

define('CRON_DEBUG_MODE', 1);
define('CRON_DEBUG_MODE_TIME', 1);

define('CRON_BASE_PATH', __DIR__);
define('CRON_BASE_PATH_KEY', 'key');

define('CRON_CHANGE_IP', 1);

define('CRON_DIR_LOG_PHPUNIT', '/auto/sh/log_phpunit');

define('CRON_BASE_DATE', "Y-m-d H:i:s");
define('CRON_BASE_LOG_DATE', "Ymd");
define('CRON_LOG_TIMEZONE', "Asia/Taipei");
?>