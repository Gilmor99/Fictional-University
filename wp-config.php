<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
define ('WP_DEBUG', false);
// ** MySQL settings ** //

if (file_exists(dirname(__FILE__) . '/local.php')) {
	/** local DB settings */
	define( 'DB_NAME', 'local' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );

} else {
	/** local DB settings */
	define( 'DB_NAME', 'gilm1406_university-data' );
	define( 'DB_USER', 'gilm1406_admin' );
	define( 'DB_PASSWORD', 'robK9RPPfWchJu5JHot' );
	define( 'DB_HOST', 'localhost' );
}


/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'hxPzsAIMkYbAAA3hbwXt6uAeu/7WsJwuwIbFx5AAGfwrZoCul/qXhkSnTO4GpGUGr+SrjYhc4Uu5KpazSsWHtQ==');
define('SECURE_AUTH_KEY',  'DWK/IYFOFGIp6rFHgJ4ahapADEDWsNBE9YaNrtouQTnAETQd2YtVSp++KqjlBRQy28O8vowyZY1h6jQ23ZNvrw==');
define('LOGGED_IN_KEY',    'mx3k8SebOCEqjzXDxp5g6Gea4MTNZ8kUwzQhVvYZRyKcVIB+4+3H4KRBErE+xlRnWJ6J7iEiCeCojEHKNLORAg==');
define('NONCE_KEY',        '2p/LnEezw2dlzlOiNsI0TrE5jLdqz2fhs2Q0rlaCTi9BTfcMBrScip22kcjyDgDbvhGKQl30Ee/fByozXwfcZA==');
define('AUTH_SALT',        'befawDhhRVu44x2UDE0NUfjLp48uFaHdjCS1aJml4SaUZPnHU4dpHe8jtrOYWLrAx26HN6iGdvaHg35UCZ8AuA==');
define('SECURE_AUTH_SALT', '+w5vLOovt/boO/58Kw3Skhymxo7zObcpAANZDBJueEGdCXOsQt3zuZtB8GRHDDcqgcm2IsDPTAzWYa91S9Wazg==');
define('LOGGED_IN_SALT',   'b6JFNLlPdUNK20waSzHZ6mgECTxrcxGmiAN2h8yoUViEK+jvkup1elX6VXg5fM+s/ri7fmI0e4x+y8UjZBDypw==');
define('NONCE_SALT',       'PX3Soc5qBD2QqtiFVsizmNp/5nMM9LS9z/F4TLJHlotKWY5XRo4Ck8Tfbm6ixhFtyAAaNVUL6lYnOiCs3/RsMw==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
