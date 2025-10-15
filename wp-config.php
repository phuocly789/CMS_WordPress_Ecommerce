<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\xampp\htdocs\wordpress_CMS\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'dailymart_cms' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4)WSh:o,+*WL@Cs#Ka=>6D)n+xC_R|VE55z^L+lq:SBF7Im,Mzs$2oozx,%7]r}u' );
define( 'SECURE_AUTH_KEY',  '3RzDBLB|[3Vx&chE&Dn#|e_OER&bN2tJd.rS|/RpkHTl<%F^}.wk!Gf5U]?1`J7t' );
define( 'LOGGED_IN_KEY',    'yx8iU*$4aP^x$&vrtx@C&#]B*@4Sd9bu|=b(/:Id,09)/Ur9G=B>kptSt1Mh_~.1' );
define( 'NONCE_KEY',        '6/zz35svwjEf5o/aQn^c_t%tXy|<Xhm,/yjHG*)?}fT^4WTJb]nL;@ydl;5_v[3y' );
define( 'AUTH_SALT',        'A[?L)4nZq|})3BWQ.$0%iH%sbzm9H&*|4^MkrVR9IGm>JRJ.a?NbROg@BL9LspM$' );
define( 'SECURE_AUTH_SALT', 'p{ODJ/Je`]$%gm>*W,RGzp_nFsVbrj{v!DqwZ`?&oA9ugMDn|%[$5 eX9-o ZL3-' );
define( 'LOGGED_IN_SALT',   'QpZHP2Q!z yFo>K9;X,WyNnPDQC:wMP@*BqMRGu-n@g.V/8npjby%c</D,5T4TrR' );
define( 'NONCE_SALT',       'ZlTS,S[P9a:hu.,RC!B!,v-F[Recl80Sv,N5dhu%<iO bpGH]&$OB4sIVqGjd8ai' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
