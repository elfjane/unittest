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

define('CRON_ADMIN_URL', 'http://s-sdk-dev-admin.7senses.com/app/');
define('CRON_SERVICE_URL', 'http://s-sdk-dev-service.7senses.com/app/');
define('CRON_PAYMENT_URL', 'http://s-sdk-dev-payment.7senses.internal/app/');

define('CRON_CHANGE_IP', 1);
?>