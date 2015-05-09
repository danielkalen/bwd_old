<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bwd' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']|)kx!=N`:wFwNimFWl.-,Jmb?Lk]a@PEXpB(s+*OK>~`$Hd^KEf0h`~yBPmUmY!');
define('SECURE_AUTH_KEY',  'FR~=X)xD*-|E!^[0Bj^Q0sPMpG8k2&,-?!#X~fwk~J5+NyLFSe` 4>_i.0V 2(t.');
define('LOGGED_IN_KEY',    'F-8{O:|Tb[;TiR$L@gD)7,21X9mGRMw/Y-061l.^b_;r@q.c;~G>POA(3Yp8Qs/c');
define('NONCE_KEY',        'i[k=.fK&CIfs~HEy75jpQgDUM;1,5|De0CB+<x*j03|?s]Cwaen5|qA4*`UFq{`Q');
define('AUTH_SALT',        'C-|A|OIi_|q(Fe7M*$4v9Zg/o;E+u-k&+&QH*|dc4@%WQx-sp%dJAOzU|!n N!Sd');
define('SECURE_AUTH_SALT', 'TmKO2V3={+r%_@Q:V{ZDyBwOg;L.ln?dwtP9Q@}o1VyCAutJInn;Rzx`FZ75ns4=');
define('LOGGED_IN_SALT',   'VcUzDf1+}+OQc|y23F^%T&`#(LoiejI!dP+gwX^C);?z8Mx_q~;#{e~u=r;8tgdg');
define('NONCE_SALT',       'JO4@~06D+{sd^b6c[Me5r(Ul0!`8;nEZ*,x P}@:Fpf.-MdMdDq*~%4QgCXH2-,y');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bwd_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
define('WPCF7_AUTOP', false); 
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
// define('WP_MEMORY_LIMIT', '256M');
// define( 'WP_POST_REVISIONS', false );
require_once(ABSPATH . 'wp-settings.php');