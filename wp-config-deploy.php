<?php
/**
 * WordPress Deploy Configuration Template
 *
 * Este archivo es usado por GitHub Actions para generar el wp-config.php
 * en producción. Los marcadores {{...}} se reemplazan con GitHub Secrets.
 *
 * NO editar directamente en producción.
 */

// ** Database settings ** //
define( 'DB_NAME', '{{DB_NAME}}' );
define( 'DB_USER', '{{DB_USER}}' );
define( 'DB_PASSWORD', '{{DB_PASSWORD}}' );
define( 'DB_HOST', '{{DB_HOST}}' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         '6wu482hm2hnkpwptkgwjfmyn2r9fqa7gtivow7vqkqzkawibrbvwyi5fg43z2zmb' );
define( 'SECURE_AUTH_KEY',  'n0hvemxqasy9kkkyfoghfbodms8ypivxbjo69tvw9lrz0e2lmhxzdoxfs8phgudf' );
define( 'LOGGED_IN_KEY',    'pocrc2nvb4vlkuaptsn6rccjnifdyimc2xyihvassfhaodbtohn1ouxvo0duaz0u' );
define( 'NONCE_KEY',        'za17tiedoc1pfcrl1tk91ddsn9ve4imrwwpmbqey37aecuflvh2n7rtsusfvumu1' );
define( 'AUTH_SALT',        '2aoikilkp7qyb7hrnua4bmi7opjmhfzyxfgsumgwqhp2etpdehfwf4yjzu3k1de4' );
define( 'SECURE_AUTH_SALT', 'vwfok3zq1brk8szjmgnnxjebv1a5ja6atnpvnmuwq1ts2p5xbd6k97xqhwioafpu' );
define( 'LOGGED_IN_SALT',   '9dsb8iccpjwxvfmtarwpxec9fzde37pllhwxugoixqplyfo5koydh0yxyk7oghmg' );
define( 'NONCE_SALT',       'bseqpmn3kuyemjwdqtl7tsr7nbzdsbrt4i5141vbmmbzrzpt77wpixywxw2hgrik' );

/**
 * WordPress database table prefix.
 */
$table_prefix = 'libros1_';

/**
 * WordPress debugging mode.
 */
define( 'WP_DEBUG', false );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
