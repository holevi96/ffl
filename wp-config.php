<?php
/** 
 * A WordPress fő konfigurációs állománya
 *
 * Ebben a fájlban a következő beállításokat lehet megtenni: MySQL beállítások
 * tábla előtagok, titkos kulcsok, a WordPress nyelve, és ABSPATH.
 * További információ a fájl lehetséges opcióiról angolul itt található:
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php} 
 *  A MySQL beállításokat a szolgáltatónktól kell kérni.
 *
 * Ebből a fájlból készül el a telepítési folyamat közben a wp-config.php
 * állomány. Nem kötelező a webes telepítés használata, elegendő átnevezni 
 * "wp-config.php" névre, és kitölteni az értékeket.
 *
 * @package WordPress
 */

// ** MySQL beállítások - Ezeket a szolgálatótól lehet beszerezni ** //
/** Adatbázis neve */
define('DB_NAME', 'ffl');

/** MySQL felhasználónév */
define('DB_USER', 'root');

/** MySQL jelszó. */
define('DB_PASSWORD', '');

/** MySQL  kiszolgáló neve */
define('DB_HOST', 'localhost');

/** Az adatbázis karakter kódolása */
define('DB_CHARSET', 'utf8mb4');

/** Az adatbázis egybevetése */
define('DB_COLLATE', '');

/**#@+
 * Bejelentkezést tikosító kulcsok
 *
 * Változtassuk meg a lenti konstansok értékét egy-egy tetszóleges mondatra.
 * Generálhatunk is ilyen kulcsokat a {@link http://api.wordpress.org/secret-key/1.1/ WordPress.org titkos kulcs szolgáltatásával}
 * Ezeknek a kulcsoknak a módosításával bármikor kiléptethető az összes bejelentkezett felhasználó az oldalról. 
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '5+X~ >h]PuXd2AnL4YX6Iy.d|d|^+,>!P^>8 X_c;Q(Qb%3HxB8AilYTN ~u||%N');
define('SECURE_AUTH_KEY', '!/:]RHr .ES2$@R%X}ULvnCL1Xt2vMP?wus/?*@sF>dZK>!q-RW8Z}[6`g0]+`kK');
define('LOGGED_IN_KEY', 'hFyr(g[H0T IkV0$Q=*6q,hrmB0(VB<_VX8t{DfiRWPZ1F`E`F4DU|ijwTpYx[4+');
define('NONCE_KEY', '} y^vJq}n_E2Co1p5cJ%52)wX`*[0t3}1Qf<p|><==8K_5?~1e<S<xTtsy_UPoo0');
define('AUTH_SALT',        'c0>2qI7I;vp[|Qy$XX^aohdmRU|ZAnb#psgkgdGreaRg3Po%UTnA`~3Z:?fq8+d ');
define('SECURE_AUTH_SALT', 'whd>ebT*LQY~-jJsNk$oj9?NSr0NX5_%xeME%n&}Z[5kBE,Y5<|uzlx{hsR6`tT`');
define('LOGGED_IN_SALT',   'i]X-uAA^#^:`C9r:Xx<W[Z}?itP93Dr5!M8AFm@:-[Kv9[?v-W&)7Ei*)wrXZTy(');
define('NONCE_SALT',       'S9qgu}DE8SbbJqYKw3@o<uON2/*q`UM3xTI&@56e}Nxh!:;X<1Wk9hP3PLT%~`}K');

/**#@-*/

/**
 * WordPress-adatbázis tábla előtag.
 *
 * Több blogot is telepíthetünk egy adatbázisba, ha valamennyinek egyedi
 * előtagot adunk. Csak számokat, betűket és alulvonásokat adhatunk meg.
 */
$table_prefix  = 'wp_';

/**
 * Fejlesztőknek: WordPress hibakereső mód.
 *
 * Engedélyezzük ezt a megjegyzések megjelenítéséhez a fejlesztés során. 
 * Erősen ajánlott, hogy a bővítmény- és sablonfejlesztők használják a WP_DEBUG
 * konstansot.
 */
define('WP_DEBUG', false);

/* Ennyi volt, kellemes blogolást! */

/** A WordPress könyvtár abszolút elérési útja. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Betöltjük a WordPress változókat és szükséges fájlokat. */
require_once(ABSPATH . 'wp-settings.php');
