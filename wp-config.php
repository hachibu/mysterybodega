<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$wp_env = getenv('WP_ENV');

if ($wp_env == 'development') {
  ini_set('display_errors', E_ALL);
  define('WP_DEBUG_DISPLAY', true);
  define('WP_DEBUG', true);
} else {
  ini_set('display_errors', 0);
  define('WP_DEBUG_DISPLAY', false);
  define('WP_DEBUG', false);
}

$env_keys = array(
  'DB_HOST',
  'DB_NAME',
  'DB_USER',
  'DB_PASSWORD',
  'AUTH_KEY',
  'SECURE_AUTH_KEY',
  'LOGGED_IN_KEY',
  'NONCE_KEY',
  'AUTH_SALT',
  'SECURE_AUTH_SALT',
  'LOGGED_IN_SALT',
  'NONCE_SALT'
);

foreach ($env_keys as $env_key) {
  define($env_key, getenv($env_key));
}

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('AUTOMATIC_UPDATER_DISABLED', false);
define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content');

$table_prefix = 'wp_';

if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/wp/');
}

require_once(ABSPATH . 'wp-settings.php');
