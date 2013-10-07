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
define('DB_NAME', 'restyle_wordpress');

/** MySQL database username */
define('DB_USER', 'restyle_wordpres');

/** MySQL database password */
define('DB_PASSWORD', 'y4rk6807');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'k]VPwn=zn/69IX}(iH?Y!Ky>)wHS`ruHvO=]9.d$l1f#vz)_KJR^fMACp`zAp,G.');
define('SECURE_AUTH_KEY',  'u@ZK_Me--kG(:~-u/VDM1Gi0?#eoNQK1@i>>NDv:oJ|+@JjR+b(2Ov~Kz0{<I74`');
define('LOGGED_IN_KEY',    '-Rg_K{|Aaj=sOK1>lZn3h$S_h?o`Bjx@eo|e@:.XhBL>cvM01vp^NW*Q7v<*y{Q8');
define('NONCE_KEY',        '%hz<<cH E{6]@;c%hv`Pfb#wwn0EI&Ri$-/Yim-.V:,v7-;D4U@Z=Z+&G|:66&TL');
define('AUTH_SALT',        '|R=J91Ie*$vqb~f|I4{up-<b-rL.FYh5~>;mCCKv{tF/8<O4oNYU5caFb@w|nE@0');
define('SECURE_AUTH_SALT', '+H+{:=,qJvc>cYKe_nmDZ>Ab&cLeU;L+KI;S|E!H?*`|bHxg#^nvq&/=YM7o+-Gv');
define('LOGGED_IN_SALT',   'k;g&z*fGUv<6q!/}2%VQW(]Q1zI-uec{e*qfYf`#K__Lw},sHO|TM/)#% ,}xig9');
define('NONCE_SALT',       '^U~mRV+U:x1Q{9q5fk%W]5rxK-ec nV]Z1|^IiW^sV.AL.x2beR[u>DmwFiE78K<');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
