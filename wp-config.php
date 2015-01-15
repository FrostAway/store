<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'store_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '|nZ`1XsE^!p:=nP*+A*1[/vO#n?-Oi_^=[x$J5<c#%j$N-pxTQrq)_IZlig):h$j');
define('SECURE_AUTH_KEY',  'AHQ`uai(.(ctEM)Nm(O0(5596L*x`+(k<3c(~p{f2]c9A2avgj5#LY8+?Vc&h#S=');
define('LOGGED_IN_KEY',    '7O+={p!MQ4dFUh WW4mqh(Nm]A@;o^y_v8]Is&F%3ZQda@p6)QT-`?<wMw-DAJ?r');
define('NONCE_KEY',        '}j.!x,#APBmIpT,2|@m,;fs3RqOu%?Ki@5J/?E_*/bWzmwDMLchS)(+-K<|v>3S<');
define('AUTH_SALT',        ':^nN*tK0M|rk+Rs|I1ae{l~3*|@-uS<c9u@Ykna@_T~JjH-B{^*{J>Qp65*TB9kM');
define('SECURE_AUTH_SALT', ':][%$O>gDB,lLnXC!2Q{f$IMb=Z+F!MUS@RSQ/DVMY?0M;7}?Dc#>>auuEN&E6n$');
define('LOGGED_IN_SALT',   'Q;<N&@DVkjv^N=Kn.&Pc6-B#fqb/QL)dw5Cy=q..VBC_!Jx{!3jqk2/ReC|h~#$Q');
define('NONCE_SALT',       'q(|It!gF* YY]|@F&<RV3CcT)->T%i4[Q6}hUi6A0.PN<tt3KaV|p&QiQ0{lP}h,');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
